<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskMitigation extends Model
{
    /** @use HasFactory<\Database\Factories\RiskMitigationFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'deal_id',
        'category',
        'risk',
        'likelihood',
        'impact',
        'mitigations',
    ];

    protected $casts = [
        'likelihood' => 'float',
        'impact' => 'float',
        'mitigations' => 'array',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
