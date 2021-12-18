<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreignId('label_id');
            $table->foreign('label_id')->references('id')->on('labels');
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
        Schema::dropIfExists('label_project');
    }
}
