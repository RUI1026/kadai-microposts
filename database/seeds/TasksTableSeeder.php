<?php

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
     DB::table('users')->insert(
            [
                
                'name' => 'test1',
                'email' => 'test1@example.com',
                'password' => Hash::make('123456789')
            ]);
     DB::table('users')->insert(
            [
                
                'name' => 'test2',
                'email' => 'test2@example.com',
                'password' => Hash::make('123456789')
            ]);            
     DB::table('users')->insert(
            [
                
                'name' => 'test3',
                'email' => 'test3@example.com',
                'password' => Hash::make('123456789')
            ]);
    DB::table('users')->insert(
            [
                
                'name' => 'test4',
                'email' => 'test4@example.com',
                'password' => Hash::make('123456789')
            ]);
    }
}
