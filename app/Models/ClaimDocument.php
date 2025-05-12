<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'document_type',
        'file_path',
        'status_id',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
