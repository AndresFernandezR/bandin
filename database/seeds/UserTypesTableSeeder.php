<?php

use Illuminate\Database\Seeder;
use App\Models\UserType;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::create([
            'type' => 'Administrador'
        ]);

        UserType::create([
            'type' => 'Empresa'
        ]);

        UserType::create([
            'type' => 'Franquicia',
            'acronym' => 'F'
        ]);

        UserType::create([
            'type' => 'Sucursal',
            'acronym' => 'S'
        ]);

        UserType::create([
            'type' => 'Externos',
            'acronym' => 'Ex'
        ]);
    }
}
