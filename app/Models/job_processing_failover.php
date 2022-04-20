<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class job_processing_failover extends Model
{
    use HasFactory;
    public $table = 'job_processing_failover';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'test_type_id',
        'job_id',
        'number_id',
        'call_start_time',
        'call_connect_time',
        'call_end_time',
        'call_description_id',
        'created_on',
        'updated_on',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'call_start_time' => 'datetime',
        'call_connect_time' => 'datetime',
        'call_end_time' => 'datetime',
        'created_on' => 'datetime',
        'updated_on' => 'datetime',
    ];

    public function testtype()
    {
        return $this->belongsTo(test_type::class, 'test_type_id');
    }

    public function job()
    {
        return $this->belongsTo(job::class, 'job_id');
    }

    public function number()
    {
        return $this->belongsTo(number::class, 'number_id');
    }

    public function calldescription()
    {
        return $this->belongsTo(call_description::class, 'call_description_id');
    }
}
