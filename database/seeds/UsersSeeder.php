<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
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

        $this->command->line('');
        $this->command->info($this->count . ' test users were created.');

        // Users common accounts
        \User::all()
            ->each(function (\User $user) {
                $user->save(factory(\Account::class)->create(), ['is_owner', 1]);
            });

    }
}
