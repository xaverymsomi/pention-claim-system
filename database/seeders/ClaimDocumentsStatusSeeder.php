<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClaimDocumentsStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('claim_documents_status')->insert([
            [
                'status_name' => 'uploaded',
                'description' => 'Document has been uploaded',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_name' => 'approved',
                'description' => 'Document has been approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_name' => 'rejected',
                'description' => 'Document has been rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
