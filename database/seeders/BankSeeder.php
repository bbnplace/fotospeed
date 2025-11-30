<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['name' => 'Access Bank', 'code' => '044'],
            ['name' => 'Citibank Nigeria', 'code' => '023'],
            ['name' => 'Ecobank Nigeria', 'code' => '050'],
            ['name' => 'Fidelity Bank', 'code' => '070'],
            ['name' => 'First Bank of Nigeria', 'code' => '011'],
            ['name' => 'First City Monument Bank (FCMB)', 'code' => '214'],
            ['name' => 'Globus Bank', 'code' => '00103'],
            ['name' => 'Guaranty Trust Bank (GTBank)', 'code' => '058'],
            ['name' => 'Heritage Bank', 'code' => '030'],
            ['name' => 'Jaiz Bank', 'code' => '301'],
            ['name' => 'Keystone Bank', 'code' => '082'],
            ['name' => 'Kuda Bank', 'code' => '50211'],
            ['name' => 'Opay', 'code' => '999992'],
            ['name' => 'PalmPay', 'code' => '999991'],
            ['name' => 'Parallex Bank', 'code' => '104'],
            ['name' => 'Polaris Bank', 'code' => '076'],
            ['name' => 'Providus Bank', 'code' => '101'],
            ['name' => 'Stanbic IBTC Bank', 'code' => '221'],
            ['name' => 'Standard Chartered Bank', 'code' => '068'],
            ['name' => 'Sterling Bank', 'code' => '232'],
            ['name' => 'SunTrust Bank', 'code' => '100'],
            ['name' => 'Titan Trust Bank', 'code' => '102'],
            ['name' => 'Union Bank of Nigeria', 'code' => '032'],
            ['name' => 'United Bank for Africa (UBA)', 'code' => '033'],
            ['name' => 'Unity Bank', 'code' => '215'],
            ['name' => 'Wema Bank', 'code' => '035'],
            ['name' => 'Zenith Bank', 'code' => '057'],
        ];

        foreach ($banks as $bank) {
            \App\Models\Bank::create($bank);
        }
    }
}
