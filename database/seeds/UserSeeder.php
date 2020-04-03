<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = new DateTime();
        DB::table('users')->insert([
            'firstName' => 'mark',
            'lastName' => 'chris',
            'username' => 'chris',
            'email' => 'mucyochristian2@gmail.com',
            'phoneNumber' => '07823728610',
            'idNumber' => '011139439',
            'gender' => 'male',
            'password' => Hash::make('markchris32'),
            'role' => 'admin',
            'email_verified_at' => $dt->format('Y-m-d H:i:s'),
            'is_verified' => 1
        ]);
    }
}
