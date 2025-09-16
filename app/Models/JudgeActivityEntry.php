<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class JudgeActivityEntry extends Model
{
    protected $fillable = [
        'judge_id',
        'criminal_first_instance_avg',
        'criminal_appeal_avg',
        'criminal_cassation_avg',
        'admin_violation_first_instance_avg',
        'admin_violation_appeal_avg',
        'admin_violation_cassation_avg',
        'materials_first_instance_avg',
        'materials_appeal_avg',
        'materials_cassation_avg',
        'civil_appeal_avg',
        'civil_cassation_avg',
        'economic_first_instance_avg',
        'economic_appeal_avg',
        'economic_cassation_avg',
        'administrative_case_first_instance_avg',
        'administrative_case_appeal_avg',
        'administrative_case_cassation_avg',
        'forum_topics_count',
        'forum_comments_count',
        'min_workload_first_instance',
        'min_workload_appeal',
        'min_workload_cassation',
    ];

    protected static function booted(): void
    {
        static::observe(\App\Observers\JudgeActivityEntryObserver::class);
    }

    public function judge()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }
}
