<?php

namespace Database\Seeders;

use App\Models\Constants;
use App\Models\TransactionCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
            [
                'name' => Constants::TRANSACTION_CATEGORY_FUND_WALLET
            ],
            [
                'name' => Constants::TRANSACTION_CATEGORY_WITHDRAWAL
            ],
            [
                'name' => Constants::TRANSACTION_CATEGORY_RESERVATION
            ],
            [
                'name' => Constants::TRANSACTION_CATEGORY_ORDER
            ],
            [
                'name' => Constants::TRANSACTION_CATEGORY_FEES
            ],
        ];

        foreach ($seeds as $seed) {
            TransactionCategory::firstOrCreate($seed);
        }
    }
}
