<?php

use Illuminate\Database\Seeder;

class SessionSeedData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('session')->truncate();

		for($i=0; $i<10000; $i++)
        {
            $CreditIn = rand(1,99) * 100;
            $CreditOut = rand(1,99) * 100;
            $CoinIn = rand(1,99) * 100;
            $CoinOut = rand(1,99) * 100;

            $Datetime = rand(1490976000,1506700800);

            DB::table('session')->insert([
                'StartTime' => date("Y-m-d H:i:s", $Datetime),
                'EndTime' => date("Y-m-d H:i:s", $Datetime+50000 ),
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
