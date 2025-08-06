<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamModel extends Model
{
    protected $fillable = ['course', 'title', 'json_path'];

    public function getQuestionsAttribute() {
    $json = Storage::get($this->json_path);
    return json_decode($json, true)['questions'];
    }
}
