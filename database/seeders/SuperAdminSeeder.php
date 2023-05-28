<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #15,400,000
        #1,000,000
        $seeds = [
            [
                'name' => "Adeyemi Bolaji",
                'email' => 'ade2020@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => Carbon::now()
            ],
        ];

        foreach ($seeds as $seed) {
            $user = User::firstOrCreate(
                [
                    'email' => $seed['email']
                ],
                $seed
            );

            $user->assignRole(User::SUPER_ADMIN);
        }
    }
}
