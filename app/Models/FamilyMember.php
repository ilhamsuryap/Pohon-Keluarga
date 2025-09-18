<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'name',
        'nik',
        'gender',
        'birth_date',
        'death_date',
        'relation',
        'parent_id',
        'photo',
        'description',
        'has_children',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'has_children' => 'boolean',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function parent()
    {
        return $this->belongsTo(FamilyMember::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FamilyMember::class, 'parent_id');
    }

    public function canHaveChildren()
    {
        return !$this->has_children;
    }

    public static function findDuplicate($name, $birthDate)
    {
        return self::where('name', $name)
                   ->where('birth_date', $birthDate)
                   ->first();
    }

    public function getAgeAttribute()
    {
        $endDate = $this->death_date ?? now();
        return $this->birth_date->diffInYears($endDate);
    }

    /**
     * Find potential family members based on NIK similarity
     * NIK format: DDMMYY-KKKKKKK-SSSS
     * Where DD=date, MM=month, YY=year, KKKKKKK=area code, SSSS=sequence
     */
    public static function findByNikPattern($nik)
    {
        if (!$nik || strlen($nik) !== 16) {
            return collect();
        }

        // Extract area code (positions 6-12) for family connection
        $areaCode = substr($nik, 6, 6);
        
        return self::where('nik', 'LIKE', '______' . $areaCode . '____')
                   ->where('nik', '!=', $nik)
                   ->get();
    }

    /**
     * Check if two NIKs are from the same family area
     */
    public static function isSameFamily($nik1, $nik2)
    {
        if (!$nik1 || !$nik2 || strlen($nik1) !== 16 || strlen($nik2) !== 16) {
            return false;
        }

        // Compare area codes (positions 6-12)
        return substr($nik1, 6, 6) === substr($nik2, 6, 6);
    }

    /**
     * Auto-connect family members based on NIK
     */
    public function autoConnectByNik()
    {
        if (!$this->nik) {
            return [];
        }

        $potentialFamily = self::findByNikPattern($this->nik);
        $connections = [];

        foreach ($potentialFamily as $member) {
            // Skip if already in the same family tree
            if ($member->family_id === $this->family_id) {
                continue;
            }

            // Determine relationship based on age and gender
            $relationship = $this->determineRelationship($member);
            
            if ($relationship) {
                $connections[] = [
                    'member' => $member,
                    'suggested_relationship' => $relationship,
                    'confidence' => $this->calculateConnectionConfidence($member)
                ];
            }
        }

        return $connections;
    }

    /**
     * Determine relationship between two members based on age and gender
     */
    private function determineRelationship($otherMember)
    {
        $ageDiff = $this->birth_date->diffInYears($otherMember->birth_date);
        
        // If other member is significantly older (20+ years)
        if ($ageDiff >= 20) {
            return $otherMember->gender === 'male' ? 'father' : 'mother';
        }
        
        // If this member is significantly older (20+ years)
        if ($ageDiff <= -20) {
            return 'child';
        }
        
        // If similar age (within 15 years), likely siblings
        if (abs($ageDiff) <= 15) {
            return 'sibling';
        }
        
        return null;
    }

    /**
     * Calculate confidence score for family connection
     */
    private function calculateConnectionConfidence($otherMember)
    {
        $confidence = 50; // Base confidence
        
        // Same area code in NIK
        if (self::isSameFamily($this->nik, $otherMember->nik)) {
            $confidence += 30;
        }
        
        // Similar names (surname matching)
        $thisNameParts = explode(' ', $this->name);
        $otherNameParts = explode(' ', $otherMember->name);
        
        $commonParts = array_intersect($thisNameParts, $otherNameParts);
        if (count($commonParts) > 0) {
            $confidence += 20;
        }
        
        return min(100, $confidence);
    }

    /**
     * Get family suggestions based on NIK
     */
    public function getFamilySuggestions()
    {
        return $this->autoConnectByNik();
    }

    /**
     * Boot the model and add validation rules
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            // Check if father or mother already exists for this family
            if ($member->relation === 'father') {
                $existingFather = self::where('family_id', $member->family_id)
                    ->where('relation', 'father')
                    ->first();
                if ($existingFather) {
                    throw new \Exception('Keluarga ini sudah memiliki ayah. Setiap keluarga hanya dapat memiliki satu ayah.');
                }
            }

            if ($member->relation === 'mother') {
                $existingMother = self::where('family_id', $member->family_id)
                    ->where('relation', 'mother')
                    ->first();
                if ($existingMother) {
                    throw new \Exception('Keluarga ini sudah memiliki ibu. Setiap keluarga hanya dapat memiliki satu ibu.');
                }
            }
        });

        static::updating(function ($member) {
            // Check if trying to change relation to father/mother when one already exists
            if ($member->isDirty('relation')) {
                if ($member->relation === 'father') {
                    $existingFather = self::where('family_id', $member->family_id)
                        ->where('relation', 'father')
                        ->where('id', '!=', $member->id)
                        ->first();
                    if ($existingFather) {
                        throw new \Exception('Keluarga ini sudah memiliki ayah. Setiap keluarga hanya dapat memiliki satu ayah.');
                    }
                }

                if ($member->relation === 'mother') {
                    $existingMother = self::where('family_id', $member->family_id)
                        ->where('relation', 'mother')
                        ->where('id', '!=', $member->id)
                        ->first();
                    if ($existingMother) {
                        throw new \Exception('Keluarga ini sudah memiliki ibu. Setiap keluarga hanya dapat memiliki satu ibu.');
                    }
                }
            }
        });
    }
}