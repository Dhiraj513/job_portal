<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class job extends Model
{
    use HasFactory;

    public function jobType() {
        return $this->belongsTo(JobType::class);
    }

     public function category() {
        return $this->belongsTo(Category::class);
    }

}
