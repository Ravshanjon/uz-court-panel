<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudgeScoreEntry extends Model
{
    protected $fillable = ['appeal_id','judge_id','stage','amount','note'];
}
