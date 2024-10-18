<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Return_;

class JobApplication extends Model
{
    use HasFactory;


    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employer()
    {
        return $this->belongsTo(User::class,'employer_id');
    }
    protected $table = "job_applications";

    protected $fillable = [
        'job_id',
        'user_id',
        'employer_id',
        'applied_date'
    ];
}
