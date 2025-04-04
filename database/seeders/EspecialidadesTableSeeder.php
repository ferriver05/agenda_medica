<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            [
                'nombre' => 'Medicina General',
                'descripcion' => 'Diagnostico y tratamiento de situaciones comunes.'
            ],
            [
                'nombre' => 'Cardiología',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades del corazón y sistema cardiovascular.'
            ],
            [
                'nombre' => 'Dermatología',
                'descripcion' => 'Tratamiento de enfermedades de la piel, pelo y uñas.'
            ],
            [
                'nombre' => 'Endocrinología',
                'descripcion' => 'Tratamiento de trastornos hormonales y metabólicos como diabetes.'
            ],
            [
                'nombre' => 'Gastroenterología',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades del sistema digestivo.'
            ],
            [
                'nombre' => 'Ginecología',
                'descripcion' => 'Salud reproductiva femenina y enfermedades del aparato genital.'
            ],
            [
                'nombre' => 'Hematología',
                'descripcion' => 'Tratamiento de enfermedades de la sangre y órganos hematopoyéticos.'
            ],
            [
                'nombre' => 'Infectología',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades infecciosas.'
            ],
            [
                'nombre' => 'Medicina Interna',
                'descripcion' => 'Atención integral del adulto con enfermedades complejas.'
            ],
            [
                'nombre' => 'Nefrología',
                'descripcion' => 'Tratamiento de enfermedades renales.'
            ],
            [
                'nombre' => 'Neumología',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades respiratorias.'
            ],
            [
                'nombre' => 'Neurología',
                'descripcion' => 'Tratamiento de enfermedades del sistema nervioso.'
            ],
            [
                'nombre' => 'Oftalmología',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades oculares.'
            ],
            [
                'nombre' => 'Oncología',
                'descripcion' => 'Diagnóstico y tratamiento del cáncer.'
            ],
            [
                'nombre' => 'Ortopedia',
                'descripcion' => 'Tratamiento de enfermedades y lesiones del sistema musculoesquelético.'
            ],
            [
                'nombre' => 'Otorrinolaringología',
                'descripcion' => 'Tratamiento de enfermedades de oído, nariz y garganta.'
            ],
            [
                'nombre' => 'Pediatría',
                'descripcion' => 'Atención médica de niños y adolescentes.'
            ],
            [
                'nombre' => 'Psiquiatría',
                'descripcion' => 'Diagnóstico y tratamiento de trastornos mentales.'
            ],
            [
                'nombre' => 'Reumatología',
                'descripcion' => 'Tratamiento de enfermedades del tejido conectivo y articulaciones.'
            ],
            [
                'nombre' => 'Traumatología',
                'descripcion' => 'Tratamiento de lesiones traumáticas del sistema musculoesquelético.'
            ],
            [
                'nombre' => 'Urología',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades del tracto urinario y sistema reproductor masculino.'
            ]
        ];

        DB::table('especialidades')->insert($especialidades);
    }
}
