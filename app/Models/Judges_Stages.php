<?php

namespace App\Models;

use App\Services\JudgeRatingCalculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class Judges_Stages extends Model
{
    protected $table = 'judges_stages';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'establishment_id',
        'is_judge_stage',
        'district_types',
        'durations_id',
        'position_category_id',
        'court_type_id',
        'district_types',
        'court_names_id',
        'judge_id',
        'working_place',
        'start_date',
        'end_date',
        'counter',
    ];

    protected static function booted(): void
    {
        static::saved(function ($stage) {
            if (
                $stage->judge &&
                $stage->judge->rating > 0 &&
                $stage->number_state // yoki boshqa ishonchli maydon
            ) {
                JudgeRatingCalculator::calculate();
            }
        });
    }

    public function judges()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id');
    }

    public function court_specialty()
    {
        return $this->belongsTo(CourtSpeciality::class);

    }

    public function district_types()
    {
        return $this->belongsTo(DistrictType::class, 'district_type_id');
    }

    public function court_names()
    {
        return $this->belongsTo(CourtName::class, 'court_name_id');
    }

    public function court_type()
    {
        return $this->belongsTo(CourtType::class);
    }

    public function provinces_district()
    {
        return $this->belongsTo(ProvincesDistrict::class);
    }

    public function region()
    {
        return $this->belongsTo(Regions::class);
    }

    public function position()
    {
        return $this->belongsTo(Positions::class);
    }

    public function position_category()
    {
        return $this->belongsTo(PositionCategories::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function duration()
    {
        return $this->belongsTo(Duration::class, 'durations_id');
    }

    public function appeal()
    {

        return $this->hasMany(Appeal::class);
    }


}
