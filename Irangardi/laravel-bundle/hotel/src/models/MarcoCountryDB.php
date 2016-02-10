<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class MarcoCountryDB extends Eloquent {

	public $timestamps = false;

	protected $fillable = array('CountryNam', 'CountryCod');
	
	protected $table = 'marcopolo-country';

}
