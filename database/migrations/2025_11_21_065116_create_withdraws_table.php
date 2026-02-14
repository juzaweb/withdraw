<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 20);
            $table->uuid('withdrawable_id');
            $table->string('withdrawable_type', 190);
            $table->string('method');
            $table->decimal('amount', 16, 2);
            $table->string('status', 20)->index()->default('pending');
            $table->string('type', 50)->index();
            $table->text('note')->nullable();
            $table->json('meta')->nullable();
            $table->datetimes();

            $table->unique(['code']);
            $table->index(['withdrawable_id', 'withdrawable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
};
