<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function createBackup()
    {
        try {
            $database = config('database.connections.mysql.database');
            $backupName = 'backup_'.date('Y-m-d_His').'.sql';
            $backupPath = storage_path('app/backups/'.$backupName);
    
            // Crear directorio si no existe
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
    
            // Conexión PDO
            $pdo = new \PDO(
                'mysql:host='.config('database.connections.mysql.host').';dbname='.$database,
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password')
            );
    
            // Obtener todas las tablas
            $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
    
            $output = '';
            foreach ($tables as $table) {
                // Estructura de la tabla
                $output .= "-- Estructura para tabla `$table`\n";
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
                $output .= $createTable['Create Table'].";\n\n";
    
                // Datos de la tabla
                $rows = $pdo->query("SELECT * FROM `$table`");
                $output .= "-- Datos para la tabla `$table`\n";
                
                while ($row = $rows->fetch(\PDO::FETCH_ASSOC)) {
                    $values = array_map(function ($value) use ($pdo) {
                        return $value === null ? 'NULL' : $pdo->quote($value);
                    }, $row);
                    
                    $output .= "INSERT INTO `$table` (`".implode('`, `', array_keys($row)).'`) VALUES ('.implode(', ', $values).");\n";
                }
                $output .= "\n";
            }
    
            // Guardar archivo
            file_put_contents($backupPath, $output);
    
            if (!file_exists($backupPath) || filesize($backupPath) === 0) {
                throw new \Exception('El archivo de backup está vacío');
            }
    
            return back()->with('success', "Backup creado: $backupName");
    
        } catch (\Exception $e) {
            \Log::error("Backup failed: ".$e->getMessage());
            return back()->with('error', 'Error al crear backup: '.$e->getMessage());
        }
    }
    
    public function listBackups()
    {
        try {
            // Configuración personalizada para coincidir con tu createBackup()
            $backupDisk = Storage::disk('local'); // Usamos el disco local directamente
            $backupFolder = 'backups'; // Carpeta donde se guardan tus backups PDO
            $fileExtension = 'sql'; // Extensión de tus archivos
            
            // Obtener todos los archivos de backup
            $backupFiles = $backupDisk->files($backupFolder);
            
            // Filtrar por extensión y crear colección
            $backups = collect($backupFiles)
                ->filter(function ($file) use ($fileExtension) {
                    return Str::endsWith($file, '.'.$fileExtension);
                })
                ->map(function ($file) use ($backupDisk) {
                    $filePath = $file;
                    $lastModified = $backupDisk->lastModified($filePath);
                    
                    return [
                        'path' => $filePath,
                        'name' => basename($filePath),
                        'size' => $this->formatBytes($backupDisk->size($filePath)),
                        'date' => date('d/m/Y H:i:s', $lastModified),
                        'age' => Carbon::createFromTimestamp($lastModified)->diffForHumans()
                    ];
                })
                ->sortByDesc('date'); // Ordenar por fecha más reciente
            
            return view('dba.backups', ['backups' => $backups]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al listar backups: '.$e->getMessage());
        }
    }

    public function deleteBackup($file)
    {
        try {
            $backupDisk = Storage::disk('local');
            $filePath = 'backups/' . $file;
            
            // Verificación adicional de que es un archivo de backup válido
            if (!Str::startsWith($file, 'backup_') || !Str::endsWith($file, '.sql')) {
                abort(400, 'Archivo no parece ser un backup válido');
            }
            
            if (!$backupDisk->exists($filePath)) {
                abort(404);
            }
            
            $backupDisk->delete($filePath);
            
            return back()->with('success', 'Backup eliminado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: '.$e->getMessage());
        }
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), $precision).' '.$units[$pow];
    }
}