<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cus_name',
        'cus_store_number',
        'cus_facility_coordinator',
        'cus_facility_coordinator_contact',
        'cus_district_coordinator',
        'cus_district_coordinator_contact',
        'cus_location',
        'cus_address',
    ];

    protected $appends = [
        'cus_location',
    ];

    /**
     * ADD THE FOLLOWING METHODS TO YOUR Customer MODEL
     *
     * The 'cus_lat' and 'cus_long' attributes should exist as fields in your table schema,
     * holding standard decimal latitude and longitude coordinates.
     *
     * The 'cus_location' attribute should NOT exist in your table schema, rather it is a computed attribute,
     * which you will use as the field name for your Filament Google Maps form fields and table columns.
     *
     * You may of course strip all comments, if you don't feel verbose.
     */

    /**
    * Returns the 'cus_lat' and 'cus_long' attributes as the computed 'cus_location' attribute,
    * as a standard Google Maps style Point array with 'lat' and 'lng' attributes.
    *
    * Used by the Filament Google Maps package.
    *
    * Requires the 'cus_location' attribute be included in this model's $fillable array.
    *
    * @return array
    */

    public function getCusLocationAttribute(): array
    {
        return [
            "lat" => (float)$this->cus_lat,
            "lng" => (float)$this->cus_long,
        ];
    }

    /**
    * Takes a Google style Point array of 'lat' and 'lng' values and assigns them to the
    * 'cus_lat' and 'cus_long' attributes on this model.
    *
    * Used by the Filament Google Maps package.
    *
    * Requires the 'cus_location' attribute be included in this model's $fillable array.
    *
    * @param ?array $location
    * @return void
    */
    public function setCusLocationAttribute(?array $location): void
    {
        if (is_array($location))
        {
            $this->attributes['cus_lat'] = $location['lat'];
            $this->attributes['cus_long'] = $location['lng'];
            unset($this->attributes['cus_location']);
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
            'lat' => 'cus_lat',
            'lng' => 'cus_long',
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
        return 'cus_location';
    }

    /**
     * Create relationship between Workorders and Customers
     */
    public function workorders(): HasMany
    {
        return $this->hasMany(Workorder::class);
    }
}
