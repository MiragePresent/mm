<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\Transaction::class, function (Faker $faker) {

    /** @var boolean $is_income */
    $is_income = $faker->boolean(10);

    /** @var boolean $to_pocket */
    $to_pocket = false;

    if ( ! $is_income ) {
        $to_pocket = true;
    }

    return [
        'user_id'       =>  function () {
            return factory(\User::class)->create()->id;
        },
        'category_id'   =>  function ($row) use ($is_income) {
            if ($is_income) {
                return null;
            } else {

                /** @var \User $user */
                $user = \User::find($row['user_id']);

                if ($user->categories()->count()) {
                    return $user
                        ->categories()
                        ->inRandomOrder()
                        ->first()
                        ->id;
                }

                /** @var \Category $category */
                $category = factory(\Category::class)->create();

                $user->categories()->save($category);

                return $category->id;
            }
        },
        'parent_id' => function ($row) use ($to_pocket) {

            if ($to_pocket) {

                /** @var \User $user */
                $user = \User::find($row['user_id']);

                if ($user->transactions()->count()) {

                    return $user
                        ->paidByAccount()
                        ->first()
                        ->id;
                }

                return null;
            }

            return null;
        },

        'wallet_type' => function ($row) use ($faker) {

            if ($row['parent_id']) {

                return \Pocket::MORPH_NAME;
            }

            if ($faker->boolean(10)) {
                return \Pocket::MORPH_NAME;
            }

            return \Account::MORPH_NAME;
        },

        'wallet_id' => function ($row) use ($faker) {

            /** @var \User $user */
            $user = \User::find($row['user_id']);

            if ($row['parent_id']) {

                if ($user->pockets()->count()) {
                    return $user->pockets()->inRandomOrder()->first()->id;
                }

                /** @var \Pocket $pocket */
                $pocket = factory(\Pocket::class)->create();

                $user
                    ->pockets()
                    ->save($pocket, ['is_owner' => true]);

                return $pocket->id;
            }

            if (\Pocket::MORPH_NAME == $row['wallet_type']) {

                if ($user->pockets()->count()) {
                    return $user->pockets()->inRandomOrder()->first()->id;
                }

                /** @var \Pocket $pocket */
                $pocket = factory(\Pocket::class)->create();

                $user
                    ->pockets()
                    ->save($pocket, ['is_owner' => true]);

                return $pocket->id;
            } elseif (\Account::MORPH_NAME == $row['wallet_type']) {

                if ($user->accounts()->count()) {
                    return $user->accounts()->inRandomOrder()->first()->id;
                }

                /** @var \Pocket $account */
                $account = factory(\Pocket::class)->create();

                $user
                    ->accounts()
                    ->save($account, ['is_owner' => true]);

                return $account->id;

            }

            throw new \ErrorException('Morph type is not defined');
        },

        'amount' => function ($row) use ($faker) {
            if ($row['parent_id']) {
                return rand(10, 9999) * .01;
            }

            return $faker->boolean(90) ? rand(10, 9999) * .01 : rand(10, 9999) * -.01;
        },

        'comment'   => $faker->paragraph(3),

        'status'    => $faker->boolean(90) ? \Transaction::SUCCESS_STATUS : \Transaction::CANCELED_STATUS

    ];
});
