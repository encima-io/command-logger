<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandLoggerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_command_logger_commands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('call');
            $table->text('description')->nullable();
            $table->string('namespace');
            $table->string('interval')->nullable();
            $table->timestampsTz();
        });

        Schema::create('services_command_logger_log_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('command_id')->index();
            $table->timestampTz('started_at');
            $table->timestampTz('completed_at')->nullable();
            $table->timestampTz('failed_at')->nullable();
            $table->timestampsTz();

            $table->foreign('command_id')
                ->references('id')
                ->on('services_command_logger_commands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_command_logger_log_entries');
        Schema::dropIfExists('services_command_logger_commands');
    }
}
