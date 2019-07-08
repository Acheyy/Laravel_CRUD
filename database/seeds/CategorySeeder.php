<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(
            ['name' => 'Casa', 'parent_id' => 0,],
        );
        Category::create(
            ['name' => 'Masina', 'parent_id' => 0,],
        );
        Category::create(
            ['name' => 'Avion', 'parent_id' => 0,],
        );
        Category::create(
            ['name' => 'Masa', 'parent_id' => 1,],
        );
        Category::create(
            ['name' => 'Roata', 'parent_id' => 2,],
        );
        Category::create(
            ['name' => 'Aripa', 'parent_id' => 3,],
        );
    }
}
