<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $table_id
 * @property int $client_id
 * @property int $approved_by
 * @property \Carbon\Carbon $arrival_date
 * @property \Carbon\Carbon $departure_date
 * @property string $status
 * @property string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_id',
        'client_id',
        'approved_by',
        'arrival_date',
        'departure_date',
        'status',
        'note',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'table_id' => 'integer',
        'client_id' => 'integer',
        'approved_by' => 'integer',
        'arrival_date' => 'datetime',
        'departure_date' => 'datetime',
    ];

    public function reservationable(): MorphTo
    {
        return $this->morphTo();
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
