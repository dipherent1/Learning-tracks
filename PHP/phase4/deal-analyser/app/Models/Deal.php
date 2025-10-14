<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    /** @use HasFactory<\Database\Factories\DealFactory> */
    use HasFactory, HasUuids;

    protected $fillable =[
        'company_id',
        'user_id',
        'title',
        'description',
        'value_estimate',
        'duration_months',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parties()
    {
        return $this->hasMany(PartyProfile::class, 'deal_id');
    }

    public function risks()
    {
        return $this->hasMany(RiskMitigation::class, 'deal_id');
    }

}
