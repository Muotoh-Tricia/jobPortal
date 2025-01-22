<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobseeker extends Model
{

    protected $fillable = [
        'Email',
        'Password',
        'Name',
        'Age',
        'Gender',
        'Address',
        'Phone',
    ];
    /** @use HasFactory<\Database\Factories\JobseekerFactory> */
    use HasFactory;
}
