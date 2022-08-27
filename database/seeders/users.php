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

            DB::table('roles')->insert([
                'nom' => 'j_enquête'
            ]);

            DB::table('roles')->insert([
                'nom' => 'f_enquête'
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

                DB::table('users')->insert([
                        'numUser'=> "065566",
                        'active' => true,
                        'nom'      =>  "j_enquete",
                        'email'    => "j_enquete@gmail.com",
                        'password' => "j_enquete",
                        'idRole'   => 5
                        ]);
               DB::table('users')->insert([
                            'numUser'=> "065566666",
                            'active' => true,
                            'nom'      =>  "f_enquete",
                            'email'    => "f_enquete@gmail.com",
                            'password' => "f_enquete",
                            'idRole'   => 6
                            ]);


    }
}
