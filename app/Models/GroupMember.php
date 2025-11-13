<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'group_members';

    protected $fillable = [
        'family_id',
        'name',
        'role',
        'relation_type',
        'photo',
        'description',
    ];

    public function group()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }
}
