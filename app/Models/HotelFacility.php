<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Testing\Fluent\Concerns\Has;

class HotelFacility extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'hotel_facilities';
    protected $primarykey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'facility_name',
        'description'
    ];
}
