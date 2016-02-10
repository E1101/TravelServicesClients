<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class MarcoHotelDB extends Eloquent {

    public $timestamps = false;

    protected $fillable = array('HotelCod', 'HotelNam', 'HotelStar', 'CityNam', 'CityCod', 'RoomEmkanat', 'HotelAddress', 'CheckIn', 'CheckOut');
    
    protected $table = 'marcopolo-hotels';
}
