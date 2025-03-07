<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Application;

class JobSeeker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resume_path',
        'skills',
        'experience_years',
        'education_level'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
