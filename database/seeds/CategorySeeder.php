<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    protected $count = 20;

    protected $common = [
        'Auto',
        'Family',
        'Food',
        'Health',
        'House',
        'Rest'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->common as $title) {
            factory(\Category::class)
                ->create([
                    'title'     => $title,
                    'is_common' => 1
                ]);
        }

        factory(\Category::class, $this->count - count($this->common))
            ->create(['is_common' => 0]);
    }
}
