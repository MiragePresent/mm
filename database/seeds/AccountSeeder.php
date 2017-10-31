<?php

use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\User::count()) {
            $this->command->warn('AccountSeeder has not been started because users table is empty');
        } else {

            /** @var \Illuminate\Database\Eloquent\Collection $accounts */
            $accounts = factory(\Account::class, floor(\User::count() / rand(2,3)))
                ->create()
                ->each(function (\Account $account) {

                    /** @var \User $user */
                    $user = \User::whereDoesntHave('accounts', function ($sql) use ($account) {
                            $sql->where('account_id', '=', $account->id);
                        })
                        ->inRandomOrder()
                        ->first();

                    if ($user) {
                        $account
                            ->users()
                            ->save($user, ['is_owner' => 1]);
                    }
                });

            $accounts->take(floor($accounts->count() / 2))
                ->each(function (\Account $account) {

                    /** @var \User $user */
                    $user = \User::whereDoesntHave('accounts', function ($sql) use ($account) {
                            $sql->where('account_id', '=', $account->id);
                        })
                        ->inRandomOrder()
                        ->first();

                    if ($user) {
                        $account
                            ->users()
                            ->save($user, ['is_owner' => 0]);
                    }
                });
        }
    }
}
