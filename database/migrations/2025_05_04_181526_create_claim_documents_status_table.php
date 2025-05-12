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
        Schema::create('claim_documents_status', function (Blueprint $table) {
            $table->id();
            $table->string('status_name'); // e.g., "Pending", "Approved", "Rejected"
            $table->text('description')->nullable(); // optional field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_documents_status');
    }
};
