<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use http\Message;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Namu\WireChat\Traits\Chatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements FilamentUser
{
    use  HasApiTokens, Chatable, HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judge_id',
        'name',
        'brith_date',
        'last_name',
        'type_of_users_id',
        'position_category_id',
        'middle_name',
        'passport',
        'email',
        'password',
        'pinfl',
        'regions_id',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }

    public function region()
    {
        return $this->belongsTo(Regions::class, 'regions_id');
    }

    public function position_categories()
    {
        return $this->belongsTo(PositionCategories::class,'position_categories_id');
    }

    public function typeOfUser()
    {
        return $this->belongsTo(typeOfUser::class, 'type_of_users_id');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['malaka','judges', 'super_admin', 'panel_user']);
    }
    public function courtSpecialty()
    {
        return $this->belongsTo(CourtSpeciality::class, 'court_specialty_id');
    }
    public function judge()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }
    public function candidates()
    {
        return $this->hasMany(Candidates_document::class);
    }

}
