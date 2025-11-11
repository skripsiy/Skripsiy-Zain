<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('idTicket');
            $table->date('datereport');
            $table->string('jenisTicket');
            $table->string('notelpCust');
            $table->string('password')->nullable();
            $table->string('namacust');
            $table->integer('idlaporan')->nullable();
            $table->text('detailticket')->nullable();
            $table->integer('gamas')->nullable();
            $table->integer('lapul')->default(0);
            $table->integer('gaul')->default(0);
            $table->text('resume')->nullable();
            $table->string('klasifikasi')->nullable();
            $table->string('topic')->nullable();
            $table->string('topicDetail')->nullable();
            $table->string('noSC')->nullable();
            $table->string('statusSC')->nullable();
            $table->string('validateClose')->nullable();
            $table->string('reasonnoODS')->nullable();
            $table->string('eksalasiTicket')->nullable();
            $table->string('eksalasiVia')->nullable();
            $table->string('PIC')->nullable();
            $table->string('contact')->nullable();
            $table->string('responBE')->nullable();
            $table->text('description')->nullable();
            $table->string('reportedpriority')->nullable();
            $table->date('datesolved')->nullable();
            $table->timestamp('THT')->nullable();
            $table->string('status')->default('QUEUED');
            $table->string('regional')->nullable();
            $table->string('witel')->nullable();
            $table->string('condition')->nullable();
            $table->string('assignby')->nullable();
            $table->string('solvedby')->nullable();
            $table->string('escalationStatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
