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
                'nom' => 'proc'
            ]);

            DB::table('roles')->insert([
                'nom' => 'vice_proc'
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
            'nom'      =>  "proc",
            'email'    => "proc@gmail.com",
            'password' => "proc",
            'idRole'   => 2
            ]);

            DB::table('users')->insert([
                'numUser'=> "0655667788",
                    'active' => true,
                'nom'      =>  "vice_proc",
                'email'    => "vice_proc@gmail.com",
                'password' => "vice_proc",
                'idRole'   => 3
                ]);
                DB::table('users')->insert([
                    'numUser'=> "0655667788",
                    'active' => true,
                    'nom'      =>  "user",
                    'email'    => "user@gmail.com",
                    'password' => "user",
                    'idRole'   => 4
                    ]);


    }
}
