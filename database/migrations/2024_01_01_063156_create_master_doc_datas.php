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
        Schema::create('master_doc_datas', function (Blueprint $table) {
            $table->id();
            $table->string('temp_id')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('locker_id')->nullable();
            $table->integer('number_of_page')->nullable();
            $table->unsignedBigInteger('document_type')->nullable();
            $table->string('document_type_name')->nullable();
            $table->string('current_state')->nullable();
            $table->string('state')->nullable();
            $table->string('alternate_state')->nullable();
            $table->string('current_district')->nullable();
            $table->string('district')->nullable();
            $table->string('alternate_district')->nullable();
            $table->string('current_taluk')->nullable();
            $table->string('taluk')->nullable();
            $table->string('alternate_taluk')->nullable();
            $table->string('current_village')->nullable();
            $table->string('village')->nullable();
            $table->string('alternate_village')->nullable();
            $table->date('issued_date')->nullable();
            $table->string('document_sub_type')->nullable();
            $table->string('current_town')->nullable();
            $table->string('town')->nullable();
            $table->string('alternate_town')->nullable();
            $table->string('old_locker_number')->nullable();
            $table->string('set_id')->nullable();
            $table->text('physically')->nullable();
            $table->text('status_description')->nullable();
            $table->text('review')->nullable();
            $table->boolean('status_id')->default(0); // 0 for inactive, 1 for active
            $table->boolean('bulk_uploaded')->default(0); // 0 for inactive, 1 for active
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('document_type')->references('id')->on('master_doc_types')->onDelete('set null');
            // $table->string('document_type_name')->nullable();
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_doc_datas');
    }
};
