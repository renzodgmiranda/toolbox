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
    ];

    /**
     * Create relationship between Workorders and Customers
     */
    public function workorders(): HasMany
    {
        return $this->hasMany(Workorder::class);
    }
}
