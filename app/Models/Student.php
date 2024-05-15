<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'batchyear', 'type_of_student', 'course', 'major', 'month_uploaded'
    ];

    // Define relationships
    public function uploadedFiles()
    {
        return $this->hasMany(UploadedFile::class);
    }
}
