<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('claim_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->onDelete('cascade');
            $table->string('document_type');
            $table->string('file_path');
            $table->foreignId('status_id')->constrained('claim_documents_status')->onDelete('restrict');
            // $table->enum('status', ['uploaded', 'approved', 'rejected'])->default('uploaded');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_documents');
    }
};
