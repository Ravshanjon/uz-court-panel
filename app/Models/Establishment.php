<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    protected $table = 'establishments';

    protected $fillable = [
        'number_state',
        'region_id',
        'district_types',
        'position_id',
        'document_type_id',
        'position_category_id',
        'court_type_id',
        'court_type',
        'court_specialty_id',
        'court_type',
        'district_types',
        'court_name_id',
    ];
    public function district_types()
    {
        return $this->belongsTo(DistrictType::class,'district_type_id');
    }

    public function provinces_district()
    {
        return $this->belongsTo(ProvincesDistrict::class);
    }
    public function court_specialty()
    {
        return $this->belongsTo(CourtSpeciality::class);

    }

    public function court_names()
    {
        return $this->belongsTo(CourtName::class,'court_name_id');
    }

    public function court_type()
    {
        return $this->belongsTo(CourtType::class);
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
        return $this->belongsTo(DocumentType::class,'document_type_id');
    }

    public function judges()
    {
        return $this->hasMany(Judges::class,'judge_id');

    }
    public function judges_stages()
    {
        return $this->hasMany(Judges_Stages::class, 'judge_id');
    }
    public function candidates()
    {
        return $this->hasMany(Candidates_document::class, 'judge_id');
    }
    public function appeals()
    {
        return $this->hasMany(Appeal::class);
    }

}
