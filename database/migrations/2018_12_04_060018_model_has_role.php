<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModelHasRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     $tableNames = config('permission.table_names');
     Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {

            $table->integer('role_id')->unsigned();

            $table->morphs('model');


            $table->foreign('role_id')

                ->references('id')

                ->on($tableNames['roles'])

                ->onDelete('cascade');


            $table->primary(['role_id', 'model_id', 'model_type']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($tableNames['model_has_roles']);

    }
}
