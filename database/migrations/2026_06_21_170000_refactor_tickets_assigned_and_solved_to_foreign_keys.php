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
        // 1. Tambah kolom FK baru
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to_user_id')->nullable()->after('condition');
            $table->unsignedBigInteger('solved_by_user_id')->nullable()->after('assigned_to_user_id');

            $table->foreign('assigned_to_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('solved_by_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 2. Migrasi data lama dari string nama/email ke user ID
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            $assignedUserId = null;
            $solvedByUserId = null;

            if (!empty($ticket->assignby)) {
                $user = DB::table('users')
                    ->where('name', $ticket->assignby)
                    ->orWhere('email', $ticket->assignby)
                    ->first();
                if ($user) {
                    $assignedUserId = $user->id;
                }
            }

            if (!empty($ticket->solvedby)) {
                $user = DB::table('users')
                    ->where('name', $ticket->solvedby)
                    ->orWhere('email', $ticket->solvedby)
                    ->first();
                if ($user) {
                    $solvedByUserId = $user->id;
                }
            }

            if ($assignedUserId || $solvedByUserId) {
                DB::table('tickets')
                    ->where('idTicket', $ticket->idTicket)
                    ->update([
                        'assigned_to_user_id' => $assignedUserId,
                        'solved_by_user_id' => $solvedByUserId,
                    ]);
            }
        }

        // 3. Drop kolom string lama
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['assignby', 'solvedby']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('assignby')->nullable()->after('condition');
            $table->string('solvedby')->nullable()->after('assignby');
        });

        // Kembalikan data dari ID ke name
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            $assignby = null;
            $solvedby = null;

            if ($ticket->assigned_to_user_id) {
                $user = DB::table('users')->where('id', $ticket->assigned_to_user_id)->first();
                if ($user) {
                    $assignby = $user->name;
                }
            }

            if ($ticket->solved_by_user_id) {
                $user = DB::table('users')->where('id', $ticket->solved_by_user_id)->first();
                if ($user) {
                    $solvedby = $user->name;
                }
            }

            if ($assignby || $solvedby) {
                DB::table('tickets')
                    ->where('idTicket', $ticket->idTicket)
                    ->update([
                        'assignby' => $assignby,
                        'solvedby' => $solvedby,
                    ]);
            }
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_to_user_id']);
            $table->dropForeign(['solved_by_user_id']);
            $table->dropColumn(['assigned_to_user_id', 'solved_by_user_id']);
        });
    }
};
