<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    use HasFactory;
    protected $table = 'room_images';
    protected $primaryKey = 'd';
    public $timestamps = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id',
        'rooms_id',
        'image_path',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class, 'rooms_id', 'rooms_id');
    }

}
