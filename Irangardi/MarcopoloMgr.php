<?php

use Libraries\MarcopoloConnector;

class MarcopoloMgr extends BaseController {
	
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	
	public static function Prepare(){
		return new MarcopoloConnector("http://94.182.216.5/FarasooMarcopoloHotel/Service.asmx?wsdl",'1000001738','000695','21359906','754633');
	}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////        main fucntions           ///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




	
	public static function ReserveRoom($inputs) 
	{


		$hotel = self::Prepare();
		$ReserveNumber = $hotel->ReserveRoom($inputs) ;

		if ($ReserveNumber<=0 ) {
			if ($ReserveNumber==-10)
				die("invalid login");
			if ($ReserveNumber==-20)
				die("invalid inputs, ResLong and NumRoom must be between 1 and 10 ");
			if ($ReserveNumber==-9)
				die("no capacity");
			if ($ReserveNumber==-10)
				die("invalid inputs");

			die("unknown error");
		}

		return  $ReserveNumber ; 

	}	

	public static function InsertNamReserveRoomTemporary($inputs)
	{
		$hotel = self::Prepare();
		$result = $hotel->InsertNamReserveRoomTemporary($inputs) ;
		return $result ; 


	       
	}	

	public static function ForoshRoomFromTemporary($ReserveNo)
	{
		
		$hotel = self::Prepare();
		$result = $hotel->ForoshRoomFromTemporary($ReserveNo) ;
		return $result ; 

	 		
	}	

	public static function GetRefrenceHotel($ReserveNo)
	{
		$hotel = self::Prepare();
		$result = $hotel->GetRefrenceHotel($ReserveNo) ;
		return $result ; 
 		
	}	


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////     booking and payment fucntion        ///////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	public static function pay($params)
	{
		    
		$HotelCod= $params['HotelCod'] ;
		$RoomTypeCod= $params['RoomTypeCod'] ;
		$StDat= $params['StDat'];
		$ResLong= $params['ResLong'];
		$NumRoom= $params['NumRoom'];
		$HotelNam= $params['HotelNam'];
		$CityNam= $params['CityNam'];
		$CityCod= $params['CityCod'];
		$ReserveNumber= $params['ReserveNumber'];
		$price= $params['Price'];
		$totallprice= $params['totallprice'];
		$real_price= $params['real_price'];    // each room price without commission
		$RealTotallPrice=$real_price*$NumRoom; // total price wituot commission

		$typerooms = $params['typerooms'];  //array
		$fnames = $params['fnames'];        //array
		$lnames = $params['lnames'];        //array
		$melicodes = $params['melicodes'];  //array
		$birthdays = $params['birthdays'];  //array

		$Mobile = $params['Mobile'];      
		$TelNo1 = $params['TelNo1'];
		$TelNo2 = $params['TelNo2'];
		$Address = $params['Address'];
		$Email = $params['Email'];
		$ZipCode = $params['ZipCode'];
		$Dsc = $params['Dsc'];
		$PayByCredit = $params['PayByCredit'];


	$product = array(   
    'post_content'=>'', // tamame dataye bargashte, detail azafi hotel room 
    'post_type'=>'hotel',
    'post_status'=>'A', 
    'post_title'=>$NumRoom.' rooms on hotel '.$HotelNam.'('.$HotelCod.')for '.$ResLong.'nights' ,  
    'post_author'=>Sentry::getUser()->id,
    );

     $product_id = Posts::Addnew($product);

     $markup_percent = SettingController::GetOption('markup_hotel');

    $now = Carbon::now('utc')->toDateTimeString();

	
  $meta = array(  /// field haye mohemme content , room , hotel 
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'CityCode','meta_value'=>$CityCod),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'CityName','meta_value'=>$CityNam),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'HotelName','meta_value'=>$HotelNam),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'HotelCode','meta_value'=>$HotelCod),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'RoomTypeCode','meta_value'=>$RoomTypeCod), 
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'NumberOfRoom','meta_value'=>$NumRoom), 
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'ResLong','meta_value'=>$ResLong), 
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'EachRoomPrice','meta_value'=>$price),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'TotallPrice','meta_value'=>$totallprice),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'RealRoomPrice','meta_value'=>$real_price),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'RealTotallPrice','meta_value'=>$RealTotallPrice),
    array('post_id'=>$product_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'markup_percent','meta_value'=>$markup_percent),
    );        
   


  if(!Posts::AddMeta($meta))
  {
   $Message = 'problem with recording flight product meta data.'; 
  die('error in add product id'); 
   }

    $order = array( 
    	'post_status'=>'pending-payment', 
    	'post_title'=>'order hotel', 
    	'post_author'=>Sentry::getUser()->id ); 

    $order_id = Orders::Addnew($order);

    $now = Carbon::now('utc')->toDateTimeString();
    $prefix = UserMgr::GetUserAgency(Sentry::getUser()->id)['agency']['prefix'];

    $meta = array( //  
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'product','meta_value'=>$product_id), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'countryPhoneCode','meta_value'=>Input::get('countryPhoneCode')), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'cellnumber','meta_value'=>$Mobile), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'email','meta_value'=>$Email), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'currency','meta_value'=>Input::get('currency')), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'PayByCredit','meta_value'=>$PayByCredit), // radio butten, name method, 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'OnlinePay','meta_value'=>Input::get('OnlinePay')), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'notes','meta_value'=>trim(Input::get('notes'))), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'ref_prefix','meta_value'=>$prefix), // table ajancy 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'product_type','meta_value'=>'hotel'), 
    	array('post_id'=>$order_id, 'created_at'=>$now, 'updated_at' => $now,'meta_key'=>'ReserveNumber','meta_value'=>$ReserveNumber),
    	); 

	$Message = '';

	if(!Orders::AddMeta($meta))
		{ 
			$Message = 'problem with recording hotel order meta data.'; 
			die('erorr in add order meta data'); 
		}



	// do transaction record and get money 
	$now = Carbon::now('utc')->toDateTimeString();
	$MyCredit = UserMgr::MyCredit();
	$trans = array( 
		'Userid'=>Sentry::getUser()->id, 
		'Credit'=>'-'.$totallprice, 
		'TotalCredit'=>$MyCredit - $totallprice,  //mark upp shode
		'currency'=>'IRR', 
		'Description'=>'Ticket #'.$order_id.' ReserveNumber : '.$ReserveNumber, 
		'created_at'=>$now, 'updated_at' => $now )
	;




	if($MyCredit < $totallprice || !($Pay_id = Transactionlog::insert($trans))) 
	{
		// update order status 
		OrderDB::where('id','=',$order_id)->update(array('post_status'=>'failed')); 
		dd('Low Credit'); 
	}

	// update order payment status 
	OrderDB::where('id','=',$order_id)->update(array('post_status'=>'paied') );	
	


	$commission = SettingController::GetOption('commission_hotel');  // defult commission 

	$agency = Agency::getMyAgency(); 

	foreach ($agency['commission'] as $key => $value) 
		{ if($value['type'] == 'hotel') 
			$commission = $value['Commissionamount'];      // agancy commission 
	    }


	$commission_price = ($RealTotallPrice)*$commission/100;
	
	$trans = array( 'Userid'=>Sentry::getUser()->id, 
		'Credit'=>$commission_price, 
		'TotalCredit'=>$MyCredit - $totallprice + $commission_price, 
		'currency'=>'IRR', 
		'Description'=>'Commission for Ticket #'.$order_id, 
		'created_at'=>$now, 
		'updated_at' => $now 
		);

		if( !($Pay_id = Transactionlog::insert($trans))) 
			print_r("error. couldent add the commission to acount ");

//////////////////////////////////////////////////// finalizing /////////////////////////////////////////
		
		$hotel = self::Prepare();
		$result = $hotel->ForoshRoomFromTemporary($ReserveNumber) ;
		$result = $result->ForoshRoomFromTemporaryResult ;

		if ($result!=$ReserveNumber) { // booking error
			if ($result==-1) {
				die("error. temproary reserve time out ");
			}
			die("error. unknown booking error");			
		}
		
		

		// update order payment status 
		OrderDB::where('id','=',$order_id)->update(array('post_status'=>'completed'));


		$ticket = MarcopoloMgr::GetRefrenceHotel(63424);
		$ticket = $ticket->GetRefrenceHotelResult->ResultReserve;
		 $ticket= json_decode(json_encode($ticket),true);
	
				
		if (!isset($ticket[0])) {
        $temp = array();
        $temp = $ticket ;
        unset($ticket);
        $ticket[0]=$temp;
         }

         $counter = $NumRoom;
         for ($i=0; $i<$counter ; $i++) 
		{ 	  // passengers 

		 $FrNo= $ticket[$i]["FrNo"];
		 $FrDate= $ticket[$i]["FrDate"];
		 $FrHour= $ticket[$i]["FrHour"];
		 $VotureNo= $ticket[$i]["VotureNo"];
		 $ConfirmStatNam= $ticket[$i]["ConfirmStatNam"];
		 $Nam= $ticket[$i]["Nam"];
		 $Family= $ticket[$i]["Family"];
		 $HotelNam= $ticket[$i]["HotelNam"];
		 $RoomTypeNam= $ticket[$i]["RoomTypeNam"];
		 $DatVorod= $ticket[$i]["DatVorod"];
		 $DatKhoroj= $ticket[$i]["DatKhoroj"];
		 $ResLong= $ticket[$i]["ResLong"];
		 $NumRoom= $ticket[$i]["NumRoom"];
		 $FeeGet= $ticket[$i]["FeeGet"];

	     
	    
	     $orderitem = array(  
	    	'order_id'=>$order_id, 
	    	'order_item_name'=>'buy hotel' 
	    	); 
	    $orderitem_id = Orders::AddItem($orderitem); 

	    if(!$orderitem_id) 
	    	die('problem with adding order item $counter');


		//add order item meta data 
		$meta = array( 
		array('order_item_id'=>$orderitem_id,'meta_key'=>'name','meta_value'=>$fnames[$i]),
		array('order_item_id'=>$orderitem_id,'meta_key'=>'lname','meta_value'=>$lnames[$i]),
	    array('order_item_id'=>$orderitem_id,'meta_key'=>'melicode','meta_value'=>$melicodes[$i]), 
	    array('order_item_id'=>$orderitem_id,'meta_key'=>'birthday','meta_value'=>$birthdays[$i]),
	    array('order_item_id'=>$orderitem_id,'meta_key'=>'typeroom','meta_value'=>$typerooms[$i]) , 
	    array('order_item_id'=>$orderitem_id,'meta_key'=>'mobile','meta_value'=>$Mobile) ,
	    array('order_item_id'=>$orderitem_id,'meta_key'=>'telno','meta_value'=>$TelNo1) ,
	    array('order_item_id'=>$orderitem_id,'meta_key'=>'address','meta_value'=>$Address),
	    array('order_item_id'=>$orderitem_id,'meta_key'=>'room refrence number','meta_value'=>$FrNo),	
  	    array('order_item_id'=>$orderitem_id,'meta_key'=>'sell date','meta_value'=>$FrDate),	
  	    array('order_item_id'=>$orderitem_id,'meta_key'=>'sell time','meta_value'=>$FrHour),	
  	    array('order_item_id'=>$orderitem_id,'meta_key'=>'voucher','meta_value'=>$VotureNo),	
  	    array('order_item_id'=>$orderitem_id,'meta_key'=>'confirm status','meta_value'=>$ConfirmStatNam),	
  	    array('order_item_id'=>$orderitem_id,'meta_key'=>'fee','meta_value'=>$FeeGet),	 
		 ); 
		
		if(!Orders::AddItemMeta($meta)) 
		die('problem with adding item'.($i+1).' meta data');	
	}  // end of for



		return $order_id ;

}	




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////        extra fucntions            /////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public static function GetAllHotelList()
	{
		
		$hotel = self::Prepare();
		$result = $hotel->GetAllHotelList();
		return  $result ; 
	}	


	public static function GetHotelProperties($HotelCod) 
	{

		$hotel = self::Prepare();
		$result = $hotel->GetHotelProperties($HotelCod);
		return $result ;

	}	


	public static function GetRoomList($HotelCod) 
	{
		
	 	$hotel = self::Prepare();
		$result = $hotel->GetRoomList($HotelCod);
		return $result ;

	}	

	public static function GetRoomPriceListOneDate($HotelCod,$Dat) 
	{

	 	$hotel = self::Prepare();
		$result = $hotel->GetRoomPriceListOneDate($HotelCod,$Dat);
		return $result ;

	}	

	////////////////////////////////////////////   data entry   ///////////////////////////////////////

	public static function findAllPosts()
	{ 
		$posts = array();		
		$posts = StaticPostDB::	with('StaticPostmeta')
								->where('post_type','=','iranian-hotel')
								->orderBy('created_at','DES')
								->paginate(SettingController::PostPerPage());		

		foreach ($posts as &$post) {                            
			$post->meta = new ArrayObject();   
			foreach ($post->static_postmeta as $key => $value) {
				// get country name
				if($value->meta_key == "CountryCode"){
					$countryname = MarcoCountryDB::where("CountryCod","=",$value->meta_value)->get(array("CountryNam"))->first();
					if(!empty($countryname)){
						$post->meta["CountryName"] = $countryname["CountryNam"];
					}
					else{
						$post->meta["CountryName"] = "NAN";
					}
				}
				// get City name
				else if($value->meta_key == "CityCode"){
					$Cityname = MarcoProvinceDB::where("CityCod","=",$value->meta_value)->get(array("CityNam"))->first();
					if(!empty($Cityname)){
						$post->meta["CityName"] = $Cityname["CityNam"];
					}
					else{
						$post->meta["CityName"] = "NAN";
					}
				}
				else{
					$post->meta[$value->meta_key] = $value->meta_value;
				}
			}
		}
		return $posts;
	}

	public static function AddNewHotelData()
	{ 
	    // get all features
	    $Features = array_where(Input::all(), function($key, $value)
	    {
	        return preg_match('#^Feature_#i', $key) === 1;
	    });

	    // get all features
	    $Metas = array_where(Input::all(), function($key, $value)
	    {
	        return preg_match('#^Meta_#i', $key) === 1;
	    });

	    // get all images
	    $Images = array_where(Input::all(), function($key, $value)
	    {
	        return preg_match('#^image_upload_#i', $key) === 1;
	    });

		// check for hotel exists in irangardi DB
    	$hotel = MarcoHotelDB::where("hotelCod","=",Input::get('Meta_HotelCode'))->get()->first();
    	if( count($hotel) == 0 )
    		die("there is no hotel with this id = ".Input::get('Meta_HotelCode'));
		
		// check for existing old data for this hotel
		$postsCount = StaticPostDB::where('post_type','=','iranian-hotel')
								->where('post_uniqe_id','=',Input::get('Meta_HotelCode'))
								->count();
		if( $postsCount > 0 ){
			die("you can not add new option for this hotel . there is a row for this hotel. you may use edit button in hotel's list");
		}

		// Retrieve the hotel by the attributes, or create it if it doesn't exist... 
		// ( first used for test mode ) after finish you can change firstOrCreate to create
		$staticHotel = StaticPostDB::firstOrCreate(
								array(
									'post_uniqe_id' => Input::get('Meta_HotelCode'),
									'post_type'		=>'iranian-hotel',
									'post_title'	=>'this is '.trim($hotel['HotelNam']).' hotel details'
									)
								);
		// add new Features or update them
	    foreach ($Features as $key_feature => $value_feature) {
	    	// remove 'Feature_' from keys
	    	$key_feature = str_replace("Feature_", "", $key_feature );

			StaticPostmetaDB::firstOrCreate(
								array(
									'post_id' 		=> $staticHotel->id,
									'meta_key'		=> $key_feature,
									'meta_value'	=> $value_feature
									)
								);
		}

		// add new Metas or update them
	    foreach ($Metas as $key_meta => $value_meta) {
	    	// remove 'Meta_' from keys
	    	$key_meta = str_replace("Meta_", "", $key_meta );

			StaticPostmetaDB::firstOrCreate(
								array(
									'post_id' 		=> $staticHotel->id,
									'meta_key'		=> $key_meta,
									'meta_value'	=> $value_meta
									)
								);
		}

	    // upload images
	    foreach ($Images as $key_image => $value_image) {
		    if (Input::hasFile( $key_image ))
			{
				// image data
				$extension = Input::file( $key_image )->getClientOriginalExtension();
				$name = basename(Input::file( $key_image )->getClientOriginalName(), ".".$extension);
				$path = 'upload/hotels/'.Input::get('Meta_CountryCode').'/'.Input::get('Meta_CityCode').'/'.Input::get('Meta_HotelCode')."/";
				$size = Input::file( $key_image )->getSize();

				// check if extention allowed
				switch ( Input::file( $key_image )->getMimeType() ) {
					case 'image/jpeg':
					case 'image/jpg':
					case 'image/png':
					case 'image/bmp':
					case 'image/gif':
						break;

					default:
						die("this file type is not allowed => ".$name.".".$extension);
						break;
				}

				// upload folder exist , so may file exist
				if( is_dir( $path ) ){
					
					// remove . and .. from directory list
					$files = array_where( scandir( $path ), function($key, $value)
				    {
				    	if($value!="." && $value!=".." )
				    		return true;
				        return false;
				    });

				    // find if this file exist
					$file_exists = false;
				    foreach ($files as $key => $value)
					{
						if ( is_file ( $path . $value ) ) {
							// if same name and same size then its exists
	        				if(preg_match('#^'.$name.'#i', $value) === 1 && filesize($path.$value) == $size ){
	        					$file_exists = true;
	        					break;
	        				}
				    	}
					}
					if($file_exists){
						die("file ".$name.".".$extension." exists.");
					}

					// upload file becouse it's new
					Input::file( $key_image )->move( $path , $name.str_random(40).".".$extension );

				}// end its directory
				else{// first time upload , folder not exist

					// upload file becouse it's new
					Input::file( $key_image )->move( $path , $name.str_random(40).".".$extension );
				}
			}// end it's file
			else{

				die("Image ".$key_image." not founded!!!");
			}
	    }
	    return Redirect::route('hotel-data-entry-index');
	}

	public static function showHotelData($id){
		// get hotel detail
		$hotel = StaticPostDB::	with('StaticPostmeta')
								->where('post_type','=','iranian-hotel')
								->where('id','=',$id)
								->orderBy('created_at','DES')->get()->first();	
		
		// check if hotel exist
		if(empty($hotel))
			die("there is not such a row");

		// change meta data to usefull structure
		$hotel = $hotel->toArray();
		foreach ($hotel['static_postmeta'] as $index => $meta) {
			$hotel['meta'][ $meta [ 'meta_key' ] ] = $meta [ 'meta_value' ];
		}	

		// remove unneccessary data
		unset($hotel['static_postmeta'] );
		
		// list countries
	    $countries = array();
	    $c = MarcoCountryDB::all();
	    // change countries to usefull structure
	    foreach ($c as $key => $value) {
	      $countries [ $value [ "CountryCod" ] ] = $value [ "CountryNam" ];
	    }

	    // list cities
	    $province = MarcoProvinceDB::where("CountryCod","=",$hotel['meta']['CountryCode'])->get(array("CityNam","CityCod"))->toArray();
	    $cities = array();
	    foreach ($province as $key => $value) {
	      	$cities [ $value [ "CityCod" ] ] = trim($value['CityNam']);
	    }

	    // lis hotels and change structure
	    $hotelsData = MarcoHotelDB::where("CityCod","=",$hotel['meta']['CityCode'])->get()->toArray();
	    $hotels = array();
	    foreach ($hotelsData as $key => $value) {
			$hotels [ $value [ "HotelCod" ] ] = trim($value['HotelNam']);
	    }

	    // get current hotel irangardi data
		foreach ($hotelsData as $key => $value)
		{
			if ($value [ "HotelCod" ] == $hotel['meta']['HotelCode'] ) {
				$hotelsData = $value;
				break;
			}
		}
	    /*echo "<pre>";
		print_r($hotelsData);
		echo "</pre>";
		die();*/

		// get uploaded images from directory
		$path = 'upload/hotels/'.$hotel['meta']['CountryCode'].'/'.$hotel['meta']['CityCode'].'/'.$hotel['meta']['HotelCode']."/";
		$Images = array();
		// upload folder exist , so may file exist
		if( is_dir( $path ) ){
			
			// remove . and .. from directory list
			$files = array_where( scandir( $path ), function($key, $value)
		    {
		    	if($value!="." && $value!=".." )
		    		return true;
		        return false;
		    });

		    // generate images
			foreach ($files as $key => $Image) {
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path.$Image);
				$Images [ $key ]['image'] = $Image;
				$Images [ $key ]['encrypted'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
			}

		}// end its directory
		else{// there is nothing to show , folder not exist
			// no images
		}
		return View::make('hotel.data.edit')->with(array('userinfo'=>Sentry::getUser(),'hotelsData'=>$hotelsData,'countries'=>$countries,'cities'=>$cities,'hotels'=>$hotels,'hotel'=>$hotel,'Images'=>$Images,'status'=>Input::get('status','All')));
	}
	
	public static function UpdateHotelData($id)
	{

		$path = 'upload/hotels/'.Input::get('Meta_CountryCode').'/'.Input::get('Meta_CityCode').'/'.Input::get('Meta_HotelCode')."/";

	    // get all features
	    $Features = array_where(Input::all(), function($key, $value)
	    {
	        return preg_match('#^Feature_#i', $key) === 1;
	    });

	    // get all features
	    $Metas = array_where(Input::all(), function($key, $value)
	    {
	        return preg_match('#^Meta_#i', $key) === 1;
	    });

	    // get all images
	    $Images = array_where(Input::all(), function($key, $value)
	    {
	        return preg_match('#^image_upload_#i', $key) === 1;
	    });

	    // get old images
	    $Old_images = array_where(Input::all(), function($key, $value)
	    {
	        return preg_match('#^old_image_upload_#i', $key) === 1;
	    });
	    
	    // check for hotel exists in irangardi DB
    	$hotel = MarcoHotelDB::where("hotelCod","=",Input::get('Meta_HotelCode'))->get()->first();
    	if( count($hotel) == 0 )
    		die("there is no hotel with this id = ".Input::get('Meta_HotelCode'));
		
		// upload folder exist , so remove old images
		if( is_dir( $path ) ){
			
			// remove . and .. from directory list
			$files = array_where( scandir( $path ), function($key, $value)
		    {
		    	if($value!="." && $value!=".." )
		    		return true;
		        return false;
		    });
	
		    // remove deleted images
			$removed_images = array_diff($files, $Old_images);
		    foreach ($removed_images as $key => $value) {
		    	echo $path.$value."<br>";
		    	unlink($path.$value);
		    }

		}// end its directory

		// remove old data for this hotel
		StaticPostmetaDB::where('post_id', '=', $id)->delete();

		// add new Features or update them
	    foreach ($Features as $key_feature => $value_feature) {
	    	// remove 'Feature_' from keys
	    	$key_feature = str_replace("Feature_", "", $key_feature );

			StaticPostmetaDB::firstOrCreate(
								array(
									'post_id' 		=> $id,
									'meta_key'		=> $key_feature,
									'meta_value'	=> $value_feature
									)
								);
		}

		// add new Metas or update them
	    foreach ($Metas as $key_meta => $value_meta) {
	    	// remove 'Meta_' from keys
	    	$key_meta = str_replace("Meta_", "", $key_meta );

			StaticPostmetaDB::firstOrCreate(
								array(
									'post_id' 		=> $id,
									'meta_key'		=> $key_meta,
									'meta_value'	=> $value_meta
									)
								);
		}
		
	    // upload images
	    foreach ($Images as $key_image => $value_image) {
		    if (Input::hasFile( $key_image ))
			{
				// image data
				$extension = Input::file( $key_image )->getClientOriginalExtension();
				$name = basename(Input::file( $key_image )->getClientOriginalName(), ".".$extension);
				$size = Input::file( $key_image )->getSize();

				// check if extention allowed
				switch ( Input::file( $key_image )->getMimeType() ) {
					case 'image/jpeg':
					case 'image/jpg':
					case 'image/png':
					case 'image/bmp':
					case 'image/gif':
						break;

					default:
						die("this file type is not allowed => ".$name.".".$extension);
						break;
				}

				// upload folder exist , so may file exist
				if( is_dir( $path ) ){
					
					// remove . and .. from directory list
					$files = array_where( scandir( $path ), function($key, $value)
				    {
				    	if($value!="." && $value!=".." )
				    		return true;
				        return false;
				    });

				    // find if this file exist
					$file_exists = false;
				    foreach ($files as $key => $value)
					{
						if ( is_file ( $path . $value ) ) {
							// if same name and same size then its exists
	        				if(preg_match('#^'.$name.'#i', $value) === 1 && filesize($path.$value) == $size ){
	        					$file_exists = true;
	        					break;
	        				}
				    	}
					}
					if($file_exists){
						die("file ".$name.".".$extension." exists.");
					}

					// upload file becouse it's new
					Input::file( $key_image )->move( $path , $name.str_random(40).".".$extension );

				}// end its directory
				else{// first time upload , folder not exist

					// upload file becouse it's new
					Input::file( $key_image )->move( $path , $name.str_random(40).".".$extension );
				}
			}// end it's file
			else{

				die("Image ".$key_image." not founded!!!");
			}
	    }
	    return Redirect::route('hotel-data-entry-index');
	}


	
}
