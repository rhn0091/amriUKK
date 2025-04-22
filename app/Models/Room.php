<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'rooms_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'rooms_id',
        'room_type',
        'price',
        'total_room',
        'capacity',
        'description',
        'image',
    ];

    public function images()
    {
        return $this->hasMany(RoomImage::class, 'rooms_id', 'rooms_id');
    }
    
    public function facilities()
    {
        return $this->hasMany(RoomFacility::class, 'rooms_id', 'rooms_id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'rooms_id', 'rooms_id');
    }
}
