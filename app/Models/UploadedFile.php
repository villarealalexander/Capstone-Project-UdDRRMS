<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedFile extends Model
{
    use SoftDeletes;
    protected $table = 'uploaded_files';
    protected $fillable = ['student_id', 'file'];

    protected $dates = ['deleted_at']; 
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
