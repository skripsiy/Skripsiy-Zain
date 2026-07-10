<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_escalations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets', 'idTicket')->cascadeOnDelete();
            $table->string('escalated_to')->nullable();
            $table->string('escalated_via')->nullable();
            $table->string('contact')->nullable();
            $table->text('respon_be')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // Migrate existing escalation data
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            if ($ticket->eksalasiTicket === 'Yes' || $ticket->PIC || $ticket->eksalasiVia || $ticket->responBE || $ticket->escalationStatus) {
                DB::table('ticket_escalations')->insert([
                    'ticket_id' => $ticket->idTicket,
                    'escalated_to' => $ticket->PIC ?? null,
                    'escalated_via' => $ticket->eksalasiVia ?? null,
                    'contact' => $ticket->contact ?? null,
                    'respon_be' => $ticket->responBE ?? null,
                    'status' => $ticket->escalationStatus ?? null,
                    'created_at' => $ticket->created_at ?? now(),
                    'updated_at' => $ticket->updated_at ?? now(),
                ]);
            }
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'eksalasiTicket',
                'eksalasiVia',
                'PIC',
                'contact',
                'responBE',
                'escalationStatus',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('eksalasiTicket')->nullable();
            $table->string('eksalasiVia')->nullable();
            $table->string('PIC')->nullable();
            $table->string('contact')->nullable();
            $table->string('responBE')->nullable();
            $table->string('escalationStatus')->nullable();
        });

        // Restore data
        $escalations = DB::table('ticket_escalations')->get();
        foreach ($escalations as $esc) {
            DB::table('tickets')->where('idTicket', $esc->ticket_id)->update([
                'eksalasiTicket' => 'Yes',
                'eksalasiVia' => $esc->escalated_via,
                'PIC' => $esc->escalated_to,
                'contact' => $esc->contact,
                'responBE' => $esc->respon_be,
                'escalationStatus' => $esc->status,
            ]);
        }

        Schema::dropIfExists('ticket_escalations');
    }
};
