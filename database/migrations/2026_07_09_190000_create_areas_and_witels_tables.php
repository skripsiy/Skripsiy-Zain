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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('witels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert the 4 Areas
        $areas = [
            1 => 'Pulau Sumatra',
            2 => 'Pulau Jawa dan Bali',
            3 => 'Kalimantan',
            4 => 'Sulawesi, Maluku, Papua'
        ];

        foreach ($areas as $id => $name) {
            DB::table('areas')->insert([
                'id' => $id,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('witel_id')->nullable()->constrained('witels')->nullOnDelete();
        });

        // Migrate existing regional & witel data to areas & witels
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            if ($ticket->witel) {
                // Determine Area ID
                $regional = strtoupper(trim($ticket->regional ?? ''));
                $areaId = 2; // Default to Pulau Jawa dan Bali

                if (str_contains($regional, 'SUMA')) {
                    $areaId = 1;
                } elseif (str_contains($regional, 'KALI')) {
                    $areaId = 3;
                } elseif (str_contains($regional, 'SULA') || str_contains($regional, 'MALU') || str_contains($regional, 'PAPU') || str_contains($regional, 'KTI')) {
                    $areaId = 4;
                }

                // Find or create Witel
                $witelName = strtoupper(trim($ticket->witel));
                $witelId = DB::table('witels')->where('name', $witelName)->value('id');

                if (!$witelId) {
                    $witelId = DB::table('witels')->insertGetId([
                        'area_id' => $areaId,
                        'name' => $witelName,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                DB::table('tickets')->where('idTicket', $ticket->idTicket)->update(['witel_id' => $witelId]);
            }
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['regional', 'witel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('regional')->nullable();
            $table->string('witel')->nullable();
        });

        // Restore data
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            if ($ticket->witel_id) {
                $witel = DB::table('witels')->where('id', $ticket->witel_id)->first();
                if ($witel) {
                    $area = DB::table('areas')->where('id', $witel->area_id)->first();
                    DB::table('tickets')->where('idTicket', $ticket->idTicket)->update([
                        'witel' => $witel->name,
                        'regional' => $area ? $area->name : null
                    ]);
                }
            }
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['tickets_witel_id_foreign']);
            $table->dropColumn('witel_id');
        });

        Schema::dropIfExists('witels');
        Schema::dropIfExists('areas');
    }
};
