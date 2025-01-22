<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPortal extends Model
{
    use HasFactory;
    
    protected $table = 'job_portals';

    protected $fillable = [
        'employer_id',
        'companyLogo',
        'companyName',
        'Description',
        'Address',
        'Phone',
        'Email',
        'Salary',
        'Level',
        'Language',
        'Country',
        'Responsibility',
        'Location',
        'job_type',
        'application_deadline'
    ];

    protected $casts = [
        'Salary' => 'decimal:2',
        'application_deadline' => 'date'
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobApplies()
    {
        return $this->hasMany(JobApply::class, 'job_id');
    }
}
