<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyExpenseFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'company_id',
        'category',
        'amount',
        'usage',
        'importance',
        'quality'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}
