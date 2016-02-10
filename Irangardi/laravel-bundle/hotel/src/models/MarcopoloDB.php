<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class MarcopoloDB extends Eloquent {

/*	public $timestamps = true;

	protected $fillable = array('user_id', 'credit_type', 'currency', 'amount', 'payer_id', 'status');
	
	protected $table = 'credit';

	public function user() 
    {
        return $this->belongsTo('User','payer_id');
    }

	public function transaction() 
    {
        return $this->hasmany('Transactionlog','Creditid');
    }
*/
}
