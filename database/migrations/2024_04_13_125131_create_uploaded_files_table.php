<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadedFilesTable extends Migration
{
    public function up()
    {
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('file');
            $table->timestamps();
            $table->engine = 'InnoDB'; 
        });
    }

    public function down()
    {
        Schema::dropIfExists('uploaded_files');
    }
}
