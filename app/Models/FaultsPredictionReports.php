<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaultsPredictionReports extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'status',
        'ml_result',
        'ai_result',
        'error_message',
        'pdf_status',
        'pdf_path',
        'pdf_error_message',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
