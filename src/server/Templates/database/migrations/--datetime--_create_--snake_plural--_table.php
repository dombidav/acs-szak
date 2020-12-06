<?php

use App\Models\--pascal_singular--;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class Create--pascal_plural--Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new --pascal_singular--())->CallCreateSchema('--snake_plural--');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('--snake_plural--');
    }
}
