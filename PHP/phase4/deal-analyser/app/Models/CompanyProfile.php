<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProfile extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyProfileFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'name', 'size', 'revenue', 'industry', 'location', 'description'
    ] ;

    public function user() 
    {
        return $this->BelongsTo(User::class);
    }

    public function companyExpenses()
    {
        return $this->hasMany(CompanyExpense::class, 'company_id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'company_id');
    }
}   

