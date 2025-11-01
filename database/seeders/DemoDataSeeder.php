<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();
        Order::truncate();

        Schema::enableForeignKeyConstraints();

        $user1 = User::create([
            'name'              => 'Rame',
            'email'             => 'rame@gmail.com',
            'phone'             => '09980208310',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name'              => 'Ali',
            'email'             => 'ali@gmail.com',
            'phone'             => '09980208311',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name'              => 'Wajdi',
            'email'             => 'wajdi@gmail.com',
            'phone'             => '099802099310',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        Order::create([
            'user_id'      => $user1->id,
            'total_price'  => 120,
            'status'       => 'pending',
        ]);

        Order::create([
            'user_id'      => $user2->id,
            'total_price'  => 80,
            'status'       => 'pending',
        ]);

        Order::create([
            'user_id'      => $user3->id,
            'total_price'  => 200,
            'status'       => 'cancelled',
        ]);
    }
}
