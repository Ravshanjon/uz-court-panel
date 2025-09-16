<?php

namespace App\Models;

use App\Filament\Resources\InspectionRelationManagerResource\RelationManagers\ServiceinspectionRelationManager;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = 'regions';

    protected $fillable = ['name'];

    public function judge()
    {
        return $this->hasMany(Judges::class);
    }

    public function district_types()
    {
        return $this->belongsToMany(CourtType::class, 'court_type_region');
    }

    public function service_inspections()
    {
        return $this->hasMany(ServiceinspectionRelationManager::class);
    }

    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }
    public function appelationDate()
    {
        return $this->belongsTo(AppelationData::class);
    }
    public function users()
    {
        return $this->hasMany(User::class, 'regions_id');
    }
    public function candidates()
    {
        return $this->hasMany(Candidates_document::class);
    }
    public function judges_registry()
    {
        return $this->hasMany(JudgesRegistry::class);
    }

}
