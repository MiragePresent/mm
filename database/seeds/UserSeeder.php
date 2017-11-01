<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** @var int $count Number of users which will be generated */
    protected $count = 20;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create user from env
        if (env('APP_USER_EMAIL') && env('APP_USER_PASSWORD')) {

            /** @var string $email */
            $email = env('APP_USER_EMAIL');

            /** @var string $password */
            $password = env('APP_USER_PASSWORD');

            /** @var string $password */
            $login = env('APP_USER_LOGIN') ?: substr($email, 0, strpos($email, '@'));

            /** @var \User $env_user */
            $env_user = factory(\User::class)
                ->create([
                    'first_name'    =>  env('APP_USER_FIRST_NAME') || '',
                    'last_name'     =>  env('APP_USER_LAST_NAME') || '',
                    'email'         =>  $email,
                    'login'         =>  $login,
                    'password'      =>  $password
                ]);

            $this->command->line('');
            $this->command->info('Default user '.env('APP_USER_EMAIL').' was created.');
            $this->command->table(
                ['Email', 'Login', 'Password'],
                [[$env_user->email, $env_user->login, $env_user->password]]
            );
        }

        for ($i = 0; $i < $this->count; $i++) {
            factory(\User::class)->create();
        }

        $this->command->info($this->count . ' test users were created.');

        // Users common
        \User::all()
            ->each(function (\User $user) {
                $user
                    ->accounts()
                    ->save(factory(\Account::class)->create(), ['is_owner' => 1]);

                \Category::common()
                    ->get()
                    ->each(function (\Category $category) use ($user) {
                        $user->categories()->save($category);
                    });

            });

        // User pockets
        \User::inRandomOrder()
            ->take(floor(\User::count() / 2))
            ->get()
            ->each(function (\User $user) {
                $user
                    ->pockets()
                    ->save(factory(\Pocket::class)->create(), ['is_owner' => 1]);
            });

    }
}
