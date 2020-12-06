<?php

namespace Database\Seeders;

use App\Models\--pascal_singular--;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class --pascal_singular--Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        --pascal_singular--::factory()->count(10)->create();
    }
}
