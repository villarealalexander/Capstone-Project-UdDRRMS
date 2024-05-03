<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    protected $table = 'uploaded_files';
    protected $fillable = ['student_id', 'file'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
