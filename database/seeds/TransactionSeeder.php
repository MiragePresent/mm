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
            });
    }
}
