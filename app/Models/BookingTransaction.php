<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTransaction extends Model
{
    protected $fillable = [
        'trx_id',
        'name',
        'email',
        'phone_number',
        'total_amount',
        'is_paid',
        'place_id',
        'field_id',
        'booking_date',
        'booking_time',
        'total_sesi',
        'proof',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public static function generateUniqueTrxId(){
        $prefix = 'SPORT';
        do {
            $randomString = $prefix . mt_rand(10000, 99999);
        } while (self::where('trx_id', $randomString)->exists());

        return $randomString;
    }
}
