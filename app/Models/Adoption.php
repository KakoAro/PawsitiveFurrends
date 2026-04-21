<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    protected $fillable = [
        'pet_id','user_id','shelter_id',
        'applicant_name','applicant_email','applicant_phone',
        'address','housing_type','has_yard',
        'other_pets','reason','experience',
        'status','admin_notes','reviewed_at','reviewed_by',
    ];

    protected $casts = [
        'has_yard'    => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function pet()      { return $this->belongsTo(Pet::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function shelter()  { return $this->belongsTo(Shelter::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'warning',
            'reviewing' => 'info',
            'approved'  => 'success',
            'rejected'  => 'danger',
            'completed' => 'secondary',
            default     => 'light',
        };
    }
}
