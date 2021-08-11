<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'company_id'    => null,
            'user_type_id'  => 1,
            // 'company_key'   => null,
            'name'          => 'Admin',
            'email'         => 'admin@bandin.com',
            'password'      => bcrypt('4dminB4and1n#2021'),
        ]);
    }
}
