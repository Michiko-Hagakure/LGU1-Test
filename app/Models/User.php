<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The database connection to use (auth_db)
     *
     * @var string
     */
    protected $connection = 'auth_db';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'city',
        'is_caloocan_resident',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_caloocan_resident' => 'boolean',
        ];
    }

    /**
     * Boot the model and auto-tag Caloocan residents
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if ($user->city) {
                $user->is_caloocan_resident = (strtolower(trim($user->city)) === 'caloocan city' ||
                    strtolower(trim($user->city)) === 'caloocan');
            }
        });
    }

    /**
     * Check if user is a Caloocan City resident
     */
    public function isCaloocanResident(): bool
    {
        return $this->is_caloocan_resident;
    }

    /**
     * Get user's discount eligibility
     */
    public function getDiscountEligibility(): array
    {
        return [
            'is_caloocan_resident' => $this->is_caloocan_resident,
            'city_discount' => $this->is_caloocan_resident ? 30.00 : 0.00,
        ];
    }

    /**
     * Get the city that the user belongs to.
     */
    public function philippineCity()
    {
        return $this->belongsTo(PhilippineCity::class, 'city_id', 'id');
    }

    /**
     * Get the barangay that the user belongs to.
     */
    public function barangay()
    {
        return $this->belongsTo(PhilippineBarangay::class, 'barangay_id', 'id');
    }

    // Accessor for Avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset($this->profile_photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=064e3b&color=fff';
    }
}
