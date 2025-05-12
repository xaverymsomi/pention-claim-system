<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'status_id',
        'notes',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

}
