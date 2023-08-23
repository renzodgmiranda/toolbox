<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_problem'
    ];

    /**
     * Create relationship between Workorders and Customers
     */
    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
