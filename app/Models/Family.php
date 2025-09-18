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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->hasMany(FamilyMember::class);
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