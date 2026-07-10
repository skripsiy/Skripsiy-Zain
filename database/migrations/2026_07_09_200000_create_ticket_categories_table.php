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
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('ticket_categories')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('ticket_categories')->nullOnDelete();
        });

        // Helper function to find or create category
        $resolveCategory = function ($name, $parentId = null) {
            $name = trim($name);
            if ($name === '') return null;

            $cat = DB::table('ticket_categories')
                ->where('name', $name)
                ->where('parent_id', $parentId)
                ->first();

            if ($cat) {
                return $cat->id;
            }

            return DB::table('ticket_categories')->insertGetId([
                'name' => $name,
                'parent_id' => $parentId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        };

        // Migrate existing tickets data
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            $lastCategoryId = null;

            if ($ticket->jenisTicket) {
                $lastCategoryId = $resolveCategory($ticket->jenisTicket, null);
                
                if ($lastCategoryId && $ticket->klasifikasi) {
                    $lastCategoryId = $resolveCategory($ticket->klasifikasi, $lastCategoryId);
                    
                    if ($lastCategoryId && $ticket->topic) {
                        $lastCategoryId = $resolveCategory($ticket->topic, $lastCategoryId);
                        
                        if ($lastCategoryId && $ticket->topicDetail) {
                            $lastCategoryId = $resolveCategory($ticket->topicDetail, $lastCategoryId);
                        }
                    }
                }
            }

            if ($lastCategoryId) {
                DB::table('tickets')->where('idTicket', $ticket->idTicket)->update(['category_id' => $lastCategoryId]);
            }
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['jenisTicket', 'klasifikasi', 'topic', 'topicDetail']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('jenisTicket')->nullable();
            $table->string('klasifikasi')->nullable();
            $table->string('topic')->nullable();
            $table->string('topicDetail')->nullable();
        });

        // Restore data
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            if ($ticket->category_id) {
                // Traverse up the hierarchy to get all levels
                $path = [];
                $currentId = $ticket->category_id;
                
                while ($currentId) {
                    $cat = DB::table('ticket_categories')->where('id', $currentId)->first();
                    if ($cat) {
                        $path[] = $cat->name;
                        $currentId = $cat->parent_id;
                    } else {
                        break;
                    }
                }

                // $path is in order [leaf, ..., root], so reverse it
                $path = array_reverse($path);

                DB::table('tickets')->where('idTicket', $ticket->idTicket)->update([
                    'jenisTicket' => $path[0] ?? null,
                    'klasifikasi' => $path[1] ?? null,
                    'topic' => $path[2] ?? null,
                    'topicDetail' => $path[3] ?? null,
                ]);
            }
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['tickets_category_id_foreign']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('ticket_categories');
    }
};
