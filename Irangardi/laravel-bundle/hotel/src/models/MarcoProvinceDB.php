<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class MarcoProvinceDB extends Eloquent {

	public $timestamps = false;

	protected $fillable = array('CityCod', 'CityNam', 'CountryCod');
	
	protected $table = 'marcopolo-province';

}
