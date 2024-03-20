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
       // database/migrations/xxxx_xx_xx_xxxxxx_create_log_changes_table.php

Schema::create('log_changes', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('model_type');
    $table->unsignedBigInteger('model_id');
    $table->text('changes');
    $table->text('action');
    $table->text('original_values')->nullable();
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_changes');
    }
};
