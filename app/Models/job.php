<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class job extends Model
{
    use HasFactory;
    public $table = 'job';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'test_type_id',
        'name',
        'start_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(company::class, 'company_id');
    }

    public function testtype()
    {
        return $this->belongsTo(test_type::class, 'test_type_id');
    }

    public function procjob()
    {
        return $this->hasOne(job_processing::class, 'job_id', 'id');
    }

    public function echojob()
    {
        return $this->hasOne(job_processing_echo::class, 'job_id', 'id');
    }

    public function failjob()
    {
        return $this->hasOne(job_processing_failover::class, 'job_id', 'id');
    }
}
