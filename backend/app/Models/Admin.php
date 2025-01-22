<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    protected $fillable = [
        'Email',
        'Password',
        'Name',
        'Age',
        'Gender',
        'Phonenumber',
        'Address',
    ];
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;
}
