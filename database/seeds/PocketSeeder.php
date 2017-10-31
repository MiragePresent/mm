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
                    $pocket->save(\User::inRandomOrder()->first(), ['is_owner', 1]);
                });

            $pockets->take(floor($pockets->count() / 2))
                ->get()
                ->each(function (\Pocket $pocket) {
                    $pocket->save(\User::inRandomOrder()->first(), ['is_owner', 0]);
                });
        }
    }
}
