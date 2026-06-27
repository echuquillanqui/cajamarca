<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CajamarcaUbigeoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departamentos')->updateOrInsert(
            ['id_departamento' => '06'],
            ['descripcion' => 'Cajamarca', 'created_at' => now(), 'updated_at' => now()]
        );

        $provincias = [
            ['id_departamento' => '06', 'id_provincia' => '01', 'descripcion' => 'Cajamarca'],
            ['id_departamento' => '06', 'id_provincia' => '02', 'descripcion' => 'Cajabamba'],
            ['id_departamento' => '06', 'id_provincia' => '03', 'descripcion' => 'Celendín'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'descripcion' => 'Chota'],
            ['id_departamento' => '06', 'id_provincia' => '05', 'descripcion' => 'Contumazá'],
            ['id_departamento' => '06', 'id_provincia' => '06', 'descripcion' => 'Cutervo'],
            ['id_departamento' => '06', 'id_provincia' => '07', 'descripcion' => 'Hualgayoc'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'descripcion' => 'Jaén'],
            ['id_departamento' => '06', 'id_provincia' => '09', 'descripcion' => 'San Ignacio'],
            ['id_departamento' => '06', 'id_provincia' => '10', 'descripcion' => 'San Marcos'],
            ['id_departamento' => '06', 'id_provincia' => '11', 'descripcion' => 'San Miguel'],
            ['id_departamento' => '06', 'id_provincia' => '12', 'descripcion' => 'San Pablo'],
            ['id_departamento' => '06', 'id_provincia' => '13', 'descripcion' => 'Santa Cruz'],
        ];

        foreach ($provincias as $provincia) {
            DB::table('provincias')->updateOrInsert(
                ['id_departamento' => $provincia['id_departamento'], 'id_provincia' => $provincia['id_provincia']],
                ['descripcion' => $provincia['descripcion'], 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $distritos = [
            ['id_departamento' => '06', 'id_provincia' => '01', 'id_distrito' => '01', 'descripcion' => 'Cajamarca'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '04', 'descripcion' => 'Chiguirip'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '05', 'descripcion' => 'Chimban'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '06', 'descripcion' => 'Choropampa'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '07', 'descripcion' => 'Cochabamba'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '08', 'descripcion' => 'Conchan'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '09', 'descripcion' => 'Huambos'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '10', 'descripcion' => 'Lajas'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '11', 'descripcion' => 'Llama'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '12', 'descripcion' => 'Miracosta'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '13', 'descripcion' => 'Paccha'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '14', 'descripcion' => 'Pion'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '15', 'descripcion' => 'Querocoto'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '16', 'descripcion' => 'San Juan de Licupis'],
            ['id_departamento' => '06', 'id_provincia' => '04', 'id_distrito' => '17', 'descripcion' => 'Tacabamba'],
            ['id_departamento' => '06', 'id_provincia' => '06', 'id_distrito' => '15', 'descripcion' => 'Toribio Casanova'],
            ['id_departamento' => '06', 'id_provincia' => '07', 'id_distrito' => '01', 'descripcion' => 'Bambamarca'],
            ['id_departamento' => '06', 'id_provincia' => '07', 'id_distrito' => '02', 'descripcion' => 'Chugur'],
            ['id_departamento' => '06', 'id_provincia' => '07', 'id_distrito' => '03', 'descripcion' => 'Hualgayoc'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '01', 'descripcion' => 'Jaén'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '02', 'descripcion' => 'Bellavista'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '03', 'descripcion' => 'Chontali'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '04', 'descripcion' => 'Colasay'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '05', 'descripcion' => 'Huabal'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '06', 'descripcion' => 'Las Pirias'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '07', 'descripcion' => 'Pomahuaca'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '08', 'descripcion' => 'Pucara'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '09', 'descripcion' => 'Sallique'],
            ['id_departamento' => '06', 'id_provincia' => '08', 'id_distrito' => '10', 'descripcion' => 'San Felipe'],
            ['id_departamento' => '06', 'id_provincia' => '11', 'id_distrito' => '08', 'descripcion' => 'Nanchoc'],
            ['id_departamento' => '06', 'id_provincia' => '11', 'id_distrito' => '09', 'descripcion' => 'Niepos'],
            ['id_departamento' => '06', 'id_provincia' => '11', 'id_distrito' => '10', 'descripcion' => 'San Gregorio'],
            ['id_departamento' => '06', 'id_provincia' => '11', 'id_distrito' => '11', 'descripcion' => 'San Silvestre de Cochan'],
            ['id_departamento' => '06', 'id_provincia' => '11', 'id_distrito' => '12', 'descripcion' => 'Tongod'],
            ['id_departamento' => '06', 'id_provincia' => '11', 'id_distrito' => '13', 'descripcion' => 'Unión Agua Blanca'],
            ['id_departamento' => '06', 'id_provincia' => '12', 'id_distrito' => '01', 'descripcion' => 'San Pablo'],
            ['id_departamento' => '06', 'id_provincia' => '12', 'id_distrito' => '02', 'descripcion' => 'San Bernardino'],
        ];

        foreach ($distritos as $distrito) {
            DB::table('distritos')->updateOrInsert(
                [
                    'id_departamento' => $distrito['id_departamento'],
                    'id_provincia' => $distrito['id_provincia'],
                    'id_distrito' => $distrito['id_distrito'],
                ],
                ['descripcion' => $distrito['descripcion'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
