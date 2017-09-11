<?php

use Illuminate\Database\Seeder;

class SessionsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<100; $i++)
        {
            $CreditIn = rand(1,99) * 100;
            $CreditOut = rand(1,99) * 100;
            $CoinIn = rand(1,99) * 100;
            $CoinOut = rand(1,99) * 100;

            DB::table('session')->insert([
                'StartTime' => date("Y-m-d H:i:s", mt_rand(1504195200000,1505059199000) ),
                'EndTime' => date("Y-m-d H:i:s", mt_rand(1504195200000,1505059199000) ),
                'OperatorID' => rand(13,15),
                'TotalCreditIn' => $CreditIn,
                'TotalCreditOut' => $CreditOut,
                'TotalCoinIn' => $CoinIn,
                'TotalCoinOut' => $CoinOut,
                'Throughput' => $CreditIn - $CreditOut,
                'CoinDiff' => $CoinIn - $CoinOut
            ]);
        }
    }
}
