<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique()->index('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->integer('parent_id')->unsigned()->default(0)->nullable();
//            $table->foreign('parent_id')
//                ->references('id')
//                ->on('categories')
//                ->onDelete('set null');

            $table->text('image');
            $table->integer('position')->unsigned()->default(0);
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
