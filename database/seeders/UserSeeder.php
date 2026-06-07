<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Sistemas SuperAdmin',
            'username' => 'superadmin',
            'dni' => '11111111',
            'role' => 'superadmin',
            'email' => 'superadmin@clinica.com',
            'password' => Hash::make('12345678'), // <- Contraseña de prueba
            'email_verified_at' => now(),
        ]);

        // Administrador Clínico (Gestión de personal y turnos)
        User::create([
            'name' => 'Administrador General',
            'username' => 'admin',
            'dni' => '22222222',
            'role' => 'admin',
            'email' => 'admin@clinica.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        // Médico Nefrólogo de Prueba
        User::create([
            'name' => 'Dr. Carlos Mendoza (Nefrólogo)',
            'username' => 'carlos.medico',
            'dni' => '33333333',
            'cmp' => '074521', // Código Médico ficticio
            'rne' => '031458', // Registro Especialista ficticio
            'role' => 'medico',
            'email' => 'medico@clinica.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        // Enfermera Especialista en Diálisis de Prueba
        User::create([
            'name' => 'Lic. Ana Espinoza (Enfermería)',
            'username' => 'ana.enfermera',
            'dni' => '44444444',
            'role' => 'enfermera',
            'email' => 'enfermera@clinica.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
    }
}
