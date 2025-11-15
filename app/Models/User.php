<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
        'phone',
        'payment_amount',
        'payment_code',
        'payment_status',
        'payment_date',
        'payment_proof',
        'payment_proof_uploaded_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'payment_date' => 'datetime',
        'payment_proof_uploaded_at' => 'datetime',
        'is_approved' => 'boolean',
    ];

    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isApproved()
    {
        return $this->is_approved;
    }

    public function generatePaymentCode()
    {
        $baseAmount = $this->payment_amount ?? 50000;
        $uniqueCode = rand(100, 999);
        $this->payment_amount = $baseAmount + $uniqueCode;
        $this->payment_code = (string) $uniqueCode;
        $this->save();
        
        return $this->payment_amount;
    }

    public function hasUploadedPaymentProof()
    {
        return !empty($this->payment_proof);
    }

    public function isPendingPaymentApproval()
    {
        return $this->hasUploadedPaymentProof() && !$this->is_approved && $this->payment_status !== 'approved';
    }

    public function getPaymentProofUrl()
    {
        if ($this->payment_proof) {
            return asset('storage/' . $this->payment_proof);
        }
        return null;
    }
}