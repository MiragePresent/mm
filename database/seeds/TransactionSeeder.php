<?php

use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()
            ->each(function (\User $user) {

                factory(\Transaction::class, 10)
                    ->create(['user_id' => $user->id]);

                /** @var \Illuminate\Database\Eloquent\Collection $transactions */
                $transactions = $user
                    ->load('transactions')
                    ->transactions;

                $user
                    ->accounts
                    ->each(function (\Account $account) use ($transactions) {

                        $balance = $transactions
                            ->where('wallet_type', \Account::MORPH_NAME)
                            ->where('wallet_id', $account->id)
                            ->sum('amount');

                        $account->update(['balance' => $balance]);
                    });

                $user
                    ->pockets
                    ->each(function (\Pocket $pocket) use ($transactions) {

                        $balance = $transactions
                            ->where('wallet_type', \Pocket::MORPH_NAME)
                            ->where('wallet_id', $pocket->id)
                            ->sum('amount');

                        $pocket->update(['balance' => $balance]);
                    });
            });
    }
}
