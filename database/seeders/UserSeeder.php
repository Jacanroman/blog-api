<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'jacanroman@gmail.com'],
            [
                'name'     => 'Javier Candela',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // Author user
        $author = User::firstOrCreate(
            ['email' => 'author@recipes.com'],
            [
                'name'     => 'Jane Author',
                'password' => Hash::make('password'),
            ]
        );
        $author->assignRole('author');

        // Reader user
        $reader = User::firstOrCreate(
            ['email' => 'reader@recipes.com'],
            [
                'name'     => 'John Reader',
                'password' => Hash::make('password'),
            ]
        );
        $reader->assignRole('reader');
    }
}