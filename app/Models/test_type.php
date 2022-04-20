<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class test_type extends Model
{
    use HasFactory;
    public $table = 'test_type';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'test_type',
        'description',
        'job_processing_table',
    ];
}
