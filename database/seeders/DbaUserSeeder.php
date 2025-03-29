<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DbaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'dni' => '0511200100001',
            'name' => 'Administrador DBA',
            'email' => 'dba@gmail.com',
            'password' => Hash::make('password'),
            'rol' => 'DBA',
            'activo' => true,
            'fecha_nacimiento' => '1980-01-01',
            'telefono' => '9946-9687',
            'direccion' => 'Oficina Principal, Clínica',
            'genero' => 'masculino'
        ]);

        $this->command->info('Usuario DBA creado exitosamente!');
    }
}
