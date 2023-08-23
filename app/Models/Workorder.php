<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number', 'wo_problem', 'wo_problem_type', 'wo_description', 'wo_customer_po', 'wo_asset', 'wo_priority', 'wo_trade', 'wo_category', 'wo_tech_nte', 'wo_schedule', 'wo_status'
    ];

    /**
     * Create relationship between Workorders and Customers
     */
    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
