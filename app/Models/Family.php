<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'family_name',
        'description',
        'privacy', // 'privat', 'publik', or 'friend_only'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->hasMany(FamilyMember::class, 'family_id');
    }

    public function parents()
    {
        return $this->members()->whereIn('relation', ['father', 'mother']);
    }

    public function children()
    {
        return $this->members()->where('relation', 'child');
    }
}
