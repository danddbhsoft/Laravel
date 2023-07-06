<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        User::create([
            'name'=>'admin',
            'email'=>'dandd.bhsoft@gmail.com',
            'password'=>'$2y$10$LTdqQBnzBas6uh7.45W1P.QImUo44iwWh0N3FSr41Z8DiK5B4Vjsy',
        ])->assignRole('user', 'writer', 'admin');
        */
        User::create([
            'name'=>'worker',
            'email'=>'khaidx.bhsoft@gmail.com',
            'password'=>'$2y$10$LTdqQBnzBas6uh7.45W1P.QImUo44iwWh0N3FSr41Z8DiK5B4Vjsy',
        ])->assignRole('user', 'writer');
    }
}
