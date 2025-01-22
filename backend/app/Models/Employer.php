<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'companyname',
        'address',
        'phone',
        'age',
        'gender',
    ];

    /**
     * Get the jobs for the employer.
     */
    public function jobs()
    {
        return $this->hasMany(JobPortal::class);
    }
}