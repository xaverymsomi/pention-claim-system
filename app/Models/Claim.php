<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'status_id',
        'comments', 'assigned_to'
    ];

    /**
     * Relationships
     */

    // Each claim belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each claim has one status
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function documents()
    {
        return $this->hasMany(ClaimDocument::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ClaimStatusHistory::class)->latest();
    }
}
