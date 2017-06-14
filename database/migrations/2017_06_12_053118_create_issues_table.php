<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('assigned_to')->nullable();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['New', 'Assigned', 'Working', 'On Review', 'Approved']);
            $table->enum('type', ['ASAP', 'Urgent', '', 'End of Day', 'End of Week']);
            $table->string('image');
            $table->smallInteger('comment_count')->default(0);
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
        Schema::dropIfExists('issues');
    }
}
