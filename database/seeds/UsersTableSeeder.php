<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'email' => 'msa4rakib@gmail.com',
        ], [
            'name' => "Administrator",
            'password' => bcrypt('Akash2020'),
            'role' => 'master',
        ])->forceFill([
            'email_verified_at' => now()
        ])->save();
    }
}
