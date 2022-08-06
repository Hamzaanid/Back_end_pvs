<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
                'nom' => 'admin'
            ]);

            DB::table('roles')->insert([
                'nom' => 'vice_admin'
            ]);

            DB::table('roles')->insert([
                'nom' => 'user'
            ]);


        DB::table('users')->insert([
            'numUser'=> "0655667788",
                    'active' => true,
            'nom'      =>  "admin",
            'email'    => "admin@gmail.com",
            'password' => "admin",
            'idRole'   => 1
            ]);

            DB::table('users')->insert([
                'numUser'=> "0655667788",
                    'active' => true,
                'nom'      =>  "vice_admin",
                'email'    => "vice_admin@gmail.com",
                'password' => "vice_admin",
                'idRole'   => 2
                ]);
                DB::table('users')->insert([
                    'numUser'=> "0655667788",
                    'active' => true,
                    'nom'      =>  "user",
                    'email'    => "user@gmail.com",
                    'password' => "user",
                    'idRole'   => 3
                    ]);


    }
}
