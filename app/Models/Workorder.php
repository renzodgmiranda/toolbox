<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number', 'wo_problem', 'wo_problem_type', 'wo_description', 'wo_customer_po', 'wo_asset', 'wo_priority', 'wo_trade', 'wo_category', 'wo_tech_nte', 'wo_schedule', 'wo_status', 'user_id', 'second_user_id', 'third_user_id'
    ];

    /**
     * Create relationship between Workorders and Customers
     */
    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Create relationship between Workorders and Users (as Vendors)
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with the 2nd preferred vendor.
     */
    public function secondUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_user_id');
    }

    /**
     * Relationship with the 3rd preferred vendor.
     */
    public function thirdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'third_user_id');
    }
}
