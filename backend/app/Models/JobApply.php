<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobapply extends Model
{

    protected $fillable = [
        'Job_id',
        'Jobseeker_id',
        'Coverletter',
        'resume',
    ];
    /** @use HasFactory<\Database\Factories\JobapplyFactory> */
    use HasFactory;
}
