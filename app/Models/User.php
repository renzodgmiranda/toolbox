<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_preferred',
        'user_location',
        'user_address',
    ];

    protected $appends = [
        'user_location',
    ];

    /**
     * ADD THE FOLLOWING METHODS TO YOUR User MODEL
     *
     * The 'user_lat' and 'user_long' attributes should exist as fields in your table schema,
     * holding standard decimal latitude and longitude coordinates.
     *
     * The 'user_location' attribute should NOT exist in your table schema, rather it is a computed attribute,
     * which you will use as the field name for your Filament Google Maps form fields and table columns.
     *
     * You may of course strip all comments, if you don't feel verbose.
     */

    /**
    * Returns the 'user_lat' and 'user_long' attributes as the computed 'user_location' attribute,
    * as a standard Google Maps style Point array with 'lat' and 'lng' attributes.
    *
    * Used by the Filament Google Maps package.
    *
    * Requires the 'user_location' attribute be included in this model's $fillable array.
    *
    * @return array
    */

    public function getUserLocationAttribute(): array
    {
        return [
            "lat" => (float)$this->user_lat,
            "lng" => (float)$this->user_long,
        ];
    }

    /**
    * Takes a Google style Point array of 'lat' and 'lng' values and assigns them to the
    * 'user_lat' and 'user_long' attributes on this model.
    *
    * Used by the Filament Google Maps package.
    *
    * Requires the 'user_location' attribute be included in this model's $fillable array.
    *
    * @param ?array $location
    * @return void
    */
    public function setUserLocationAttribute(?array $location): void
    {
        if (is_array($location))
        {
            $this->attributes['user_lat'] = $location['lat'];
            $this->attributes['user_long'] = $location['lng'];
            unset($this->attributes['user_location']);
        }
    }

    /**
     * Get the lat and lng attribute/field names used on this table
     *
     * Used by the Filament Google Maps package.
     *
     * @return string[]
     */
    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'user_lat',
            'lng' => 'user_long',
        ];
    }

   /**
    * Get the name of the computed location attribute
    *
    * Used by the Filament Google Maps package.
    *
    * @return string
    */
    public static function getComputedLocation(): string
    {
        return 'user_location';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Create relationship between Workorders and Users (as Vendors)
     */
    public function workorders(): HasMany
    {
        return $this->hasMany(Workorder::class);
    }

    /**
     * Check if user has any of the given roles (Workorder Actions)
     */
    public function hasAnyRole($roles) {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }
}
