<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'group_members';

    protected $fillable = [
        'company_id',
        'name',
        'nik',
        'gender',
        'birth_date',
        'role',
        'position',
        'relation_type',
        'parent_id',
        'photo',
        'description',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function parent()
    {
        return $this->belongsTo(GroupMember::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(GroupMember::class, 'parent_id');
    }
}
