<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{

    use SoftDeletes;
    protected $fillable = ['name', 'batchyear', 'type_of_student', 'course', 'major'];

    // Migration
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('course')->nullable()->after('type_of_student');
        });
    }
    
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('course');
        });
    }

    public function uploadedFiles()
    {
        return $this->hasMany(UploadedFile::class);
    }
}
