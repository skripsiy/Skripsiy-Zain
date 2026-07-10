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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number')->index();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
        });

        // Migrate existing customer data
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            if ($ticket->notelpCust || $ticket->namacust) {
                // To avoid duplicate customers with same phone number
                $customerId = DB::table('customers')->where('phone_number', $ticket->notelpCust)->value('id');

                if (!$customerId) {
                    $customerId = DB::table('customers')->insertGetId([
                        'name' => $ticket->namacust ?? '',
                        'phone_number' => $ticket->notelpCust ?? '',
                        'password' => $ticket->password ?? null,
                        'created_at' => $ticket->created_at ?? now(),
                        'updated_at' => $ticket->updated_at ?? now(),
                    ]);
                }

                DB::table('tickets')->where('idTicket', $ticket->idTicket)->update(['customer_id' => $customerId]);
            }
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['namacust', 'notelpCust', 'password']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('namacust')->nullable();
            $table->string('notelpCust')->nullable();
            $table->string('password')->nullable();
        });

        // Restore data
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            if ($ticket->customer_id) {
                $customer = DB::table('customers')->where('id', $ticket->customer_id)->first();
                if ($customer) {
                    DB::table('tickets')->where('idTicket', $ticket->idTicket)->update([
                        'namacust' => $customer->name,
                        'notelpCust' => $customer->phone_number,
                        'password' => $customer->password,
                    ]);
                }
            }
        }

        // Apply NOT NULL constraint to restore original state
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('namacust')->nullable(false)->change();
            $table->string('notelpCust')->nullable(false)->change();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['tickets_customer_id_foreign']);
            $table->dropColumn('customer_id');
        });

        Schema::dropIfExists('customers');
    }
};
