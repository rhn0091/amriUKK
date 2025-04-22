<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pay extends Model
{
    use HasFactory;
    protected $table = 'pays';
    protected $primarykey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id',
        'reservation_id',
        'image'
    ];
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
