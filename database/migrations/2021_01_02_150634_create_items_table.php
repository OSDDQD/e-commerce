<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('slug')->unique()->index('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_digital')->default(false);
            $table->unsignedInteger('category_id')->nullable()
                ->references('id')->on('categories');
            $table->integer('position')->default(0)->nullable();
            $table->text('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
