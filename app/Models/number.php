<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class number extends Model
{
    use HasFactory;
    public $table = 'number';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'number',
        'country_code_id',
    ];

    
    public function company()
    {
        return $this->belongsTo(company::class, 'company_id');
    }

    public function country()
    {
        return $this->belongsTo(country::class, 'country_code_id');
    }
}
