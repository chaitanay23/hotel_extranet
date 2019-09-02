<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModelHasPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {

            $table->integer('permission_id')->unsigned();

            $table->morphs('model');


            $table->foreign('permission_id')

                ->references('id')

                ->on($tableNames['permissions'])

                ->onDelete('cascade');


            $table->primary(['permission_id', 'model_id', 'model_type']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
