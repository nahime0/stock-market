<?php

use App\Models\Symbol;
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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Symbol::class)->constrained();
            $table->dateTime('datetime')->index();
            $table->decimal('open', 10, 4)->default(0);
            $table->decimal('high', 10, 4)->default(0);
            $table->decimal('low', 10, 4)->default(0);
            $table->decimal('close', 10, 4)->default(0);
            $table->unsignedBigInteger('volume')->default(0);
            $table->timestamps();

            $table->unique(['symbol_id', 'datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
