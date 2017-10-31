<?php

use Illuminate\Database\Seeder;

class PocketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (!\User::count()) {
            $this->command->warn('PocketSeeder has not been started because users table is empty');
        } else {

            /** @var \Illuminate\Database\Eloquent\Collection $pockets */
            $pockets = factory(\Pocket::class, floor(\User::count() / rand(2,3)))
                ->create()
                ->each(function (\Pocket $pocket) {

                    /** @var \User $user */
                    $user = \User::whereDoesntHave('pockets', function ($sql) use ($pocket) {
                            $sql->where('pocket_id', '=', $pocket->id);
                        })
                        ->inRandomOrder()
                        ->first();

                    if ($user) {
                        $pocket
                            ->users()
                            ->save($user, ['is_owner' => 1]);
                    }
                });

            $pockets->take(floor($pockets->count() / 2))
                ->each(function (\Pocket $pocket) {

                    /** @var \User $user */
                    $user = \User::whereDoesntHave('pockets', function ($sql) use ($pocket) {
                            $sql->where('pocket_id', '=', $pocket->id);
                        })
                        ->inRandomOrder()
                        ->first();

                    if ($user) {
                        $pocket
                            ->users()
                            ->save($user, ['is_owner' => 0]);
                    }
                });
        }
    }
}
