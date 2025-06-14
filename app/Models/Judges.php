<?php

namespace App\Models;

use App\Services\JudgeRatingCalculator;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

class Judges extends Model
{
    use Notifiable;

    protected $table = 'judges';
    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // UUID is stored as string

    protected static function booted(): void
    {
        static::creating(function ($judge) {

            if (empty($judge->id)) {

                $judge->id = Str::uuid();

                $settings = RatingSetting::first();
                $judge->quality_score = $settings?->quality_score ?? 0;
                $judge->etiquette_score = $settings?->etiquette_score ?? 0;
                $judge->ethics_score = $settings?->ethics_score ?? 0;
                $judge->foreign_language_bonus = $settings?->foreign_language_bonus ?? 0;
                $judge->adding_rating = $settings?->adding_rating ?? 0;

                $judge->rating = $judge->quality_score
                    + $judge->etiquette_score
                    + $judge->ethics_score
                    + $judge->foreign_language_bonus
                    + $judge->adding_rating;
            }
        });
        static::created(function ($judge) {
            JudgeRatingHistory::create([
                'judge_id' => $judge->id,
                'rating' => $judge->rating,
            ]);
        });
        static::updating(function ($judge) {
            if (!app()->runningInConsole()) {
                $judge->rating = $judge->quality_score
                    + $judge->etiquette_score
                    + $judge->ethics_score
                    + $judge->foreign_language_bonus
                    + $judge->adding_rating;
            }
            if ($judge->isDirty('rating')) {
                JudgeRatingHistory::create([
                    'judge_id' => $judge->id,
                    'rating' => $judge->rating,
                ]);
            }

        });


    }
    protected $fillable = [
        'quality_score', 'etiquette_score', 'ethics_score', 'foreign_language_bonus', 'adding_rating',
        'rating',
        'ethics_score',
        'bonuses_id',
        'establishment_id',
        'number_state',
        'image',
        'responsive',
        'inspection',
        'region_id',
        'court_type_id',
        'provinces_district',
        'district_type',
        'court_specialty_id',
        'court_name_id',
        'position_id',
        'duration_id',
        'inspection_basis',
        'threety_region_id',
        'position_category_id',
        'provinces_district_id',
        'vacancy_start',
        'vacancy_status',
        'eligibility_submission_date',
        'documents_submitted',
        'codes',
        'last_name',
        'first_name',
        'middle_name',
        'pinfl',
        'passport_name',
        'birth_date',
        'birth_place_id',
        'birth_place',
        'address',
        'gender',
        'nationality_id',
        'appointment_date',
        'document_date',
        'document_type_id',
        'document_number',
        'previous_appointment',
        'previous_duration',
        'legal_experience',
        'judicial_experience',
        'age_extension_date',
        'university_id',
        'discipline',
        'graduation_year',
        'special_education',
        'leadership_experience',
        'leadership_reserve',
    ];


    public function court_type()
    {
        return $this->belongsTo(CourtType::class);
    }

    public function district_type()
    {
        return $this->belongsTo(DistrictType::class);
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
        return $this->belongsTo(CourtName::class, 'court_name_id');
    }

    public function position()
    {
        return $this->belongsTo(Positions::class);
    }
    public function position_category()
    {
        return $this->belongsTo(PositionCategories::class);
    }


    public function birth_place()
    {
        return $this->belongsTo(Regions::class);
    }

    public function region()
    {
        return $this->belongsTo(Regions::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function duration_id()
    {
        return $this->belongsTo(Duration::class);

    }

    public function previous_duration()
    {
        return $this->belongsTo(Duration::class, 'old_duration_id');

    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function judges_stages()
    {
        return $this->hasMany(Judges_Stages::class, 'judge_id');
    }

    public function family()
    {
        return $this->hasMany(Family::class, 'judge_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id');
    }

    public function getCurrentOrFuturePositionNameAttribute(): ?string
    {
        $judges_stages = $this->judges_stages()
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })
            ->orderBy('start_date', 'desc')
            ->first();

        return $judges_stages?->position?->name;
    }

    public function serviceinspection()
    {
        return $this->hasMany(service_inspection::class, 'judge_id');
    }

//    public function responsibilityScore()
//    {
//        return $this->hasOne(responsible_score::class, 'judge_id');
//    }

    public function getOverallScoreAttribute(): int
    {
        $ethics = $this->ethics_score ?? 0;
        $quality = $this->quality_score ?? 0;
        $etiquette = $this->etiquette_score ?? 0;
        $foreignLang = $this->foreign_language_bonus ?? 0;
        $adding = $this->adding_rating ?? 0;


        // 🟥 Jazolarni (kamchiliklar) yig'indisini hisoblash
        $penalties = $this->serviceinspection
            ? $this->serviceinspection->sum(fn($i) => $i->prision_type?->score ?? 0)
            : 0;

        // ✅ Umumiy bahoni hisoblash
        return max(0, $ethics + $quality + $etiquette + $foreignLang + $adding - $penalties);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return $query->where('judges.id', $value);
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }

    public function latestStage()
    {
        return $this->hasMany(Judges_Stages::class, 'judge_id', 'id')
            ->orderByDesc('end_date')
            ->first();
    }

    public function bonuses()
    {
        return $this->belongsToMany(Bonus::class, 'bonus_judge', 'judge_id', 'bonus_id');
    }

    public function bonusAssignments()
    {
        return $this->hasMany(BonusJudges::class, 'judge_id');
    }

    public function TypeOfDecision()
    {

        return $this->belongsTo(TypeOfDecision::class);
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'judge_id')->with(['reason.instances', 'reason.typeOfDecision']);

    }

    public function getLatestStageInfoAttribute(): ?string
    {
        $stage = $this->judges_stages()
            ->orderByDesc('end_date')
            ->first();

        if (!$stage) return null;

        return $stage->position?->name . ' (' . $stage->start_date?->format('d.m.Y') . ' - ' . $stage->end_date?->format('d.m.Y') . ')';
    }

    public function private_awards()
    {
        return $this->hasMany(PrivateAward::class, 'judges_id');
    }
    public function ratingHistories()
    {
        return $this->hasMany(JudgeRatingHistory::class, 'judge_id');
    }

}

