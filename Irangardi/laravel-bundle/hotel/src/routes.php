<?php



Route::get('/hotel',array('as'=>'hotel-dashboard','before'=>'logged',
  function(){
    $user = Sentry::getUser();
  
    $cities = MarcopoloMgr::GetCityList();
    $str    = json_decode(json_encode($cities),true);
    $data = array();   

    $str = array_values(array_sort($str, function($value)
    {
        return $value['CityNam']; // be tartibe horof alefba farsi
    }));

    foreach($str as  $city){
      $data[$city['CityCod']] = $city['CityNam'];
    }

    return View::make('hotel.index')->with(array('cities'=>$data,'userinfo'=>$user));
  }
));


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////        main fucntions           ///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::post('/hotel/searchform',array('as'=>'hotel-searchform','before'=>'logged',
  function(){
    $date = explode('-', Input::get('date'));
    $MyDateCarbon = Carbon::parse($date[1].'/'.$date[2].'/'.$date[0]);
    
    $date2 = explode('-', Input::get('date2'));
    $MyDateCarbon2 = Carbon::parse($date2[1].'/'.$date2[2].'/'.$date2[0]);
    $ResLong = $MyDateCarbon2->diffInDays($MyDateCarbon);
    
    $CityCod = Input::get('CityCod');
    $result = MarcopoloMgr::GetHotelListCity($CityCod);
    if(!isset($result->GetHotelListCityResult->Hotel)){
        die("no hotel avalaible for this city");
    }
    $result= json_decode(json_encode($result),true);
    $cityhotels = $result["GetHotelListCityResult"]["Hotel"];

    $user = Sentry::getUser();
    $cities = MarcopoloMgr::GetCityList();
    $str    = json_decode(json_encode($cities),true);
    $data = array();   

    $str = array_values(array_sort($str, function($value)
    {
        return $value['CityNam']; // be tartibe horof alefba farsi
    }));

    foreach($str as  $city){
      $data[$city['CityCod']] = $city['CityNam'];
    }

    return View::make('hotel.searchform')->with(array('cities'=>$data,
                                                      'userinfo'=>$user,
                                                      'CityCod'=> Input::get('CityCod'),
                                                      'date'=> Input::get('date'),
                                                      'date2'=> Input::get('date2'),
                                                      'ResLong'=> $ResLong,
                                                      'cityhotels'=>$cityhotels
                                                      ));
  }
));

 Route::post('/hotel/details',array('as'=>'hotel-details','before'=>'logged','before'=>'csrf',
  function(){
    $user = Sentry::getUser();
    $HotelCod = Input::get('HotelCod');
    $ResLong = Input::get('ResLong');
    $HotelNam = Input::get('HotelNam');
    $CityNam = Input::get('CityNam');
    $CityCod = Input::get('CityCod');
    // fix date to jalali
    $Dat = Input::get('Dat');
    $en_date = $Dat;
    $date = explode('-', $Dat);
    $timestamp = strtotime($date[2].'-'.$date[1].'-'.$date[0]);
    $Dat = jdate('Ymd',$timestamp,'','Asia/Tehran','en');
    $PDat = jdate('Y-m-d',$timestamp,'','Asia/Tehran','en');
    // finish fix date to jalali

    $Roomvalue = array
    (
      'HotelCod'=>$HotelCod,
      'Dat'=>$Dat,
      'Nights'=>$ResLong,
      'date'=>$en_date,
    );

    $value = array
    (
      'HotelCod'=>'required|integer',
      'Dat'=>'required|integer',
      'Nights'=>'required|numeric|Min:|Max:40',
      'date'=>'required|date',
    );

    $validator = Validator::make($Roomvalue, $value);
    if ($validator->fails()) 
      {
          
        foreach ($validator->messages()->toArray() as $key => $message)
      {
        foreach ($message as $key2 => $value) {

         
            print_r("ERROR: ".$value);
            print_r("<br>");  
        }
      }
      die();
      }

    $result= MarcopoloMgr::GetRoomPriceListDateResLong($HotelCod,$Dat,$ResLong); 
    //print_r($result);die();
    if(!isset($result->GetRoomPriceListDateResLongResult->HotelRoomTypePriceDat))
      die("no room avalaible for this request ");
    
    $result= $result->GetRoomPriceListDateResLongResult->HotelRoomTypePriceDat;

    $roomprices= json_decode(json_encode($result),true);
   

    $markup_percent = SettingController::GetOption('markup_hotel');  
    
    $Hotel = json_decode(json_encode(MarcopoloMgr::GetHotelProperties($HotelCod)),true)['GetHotelPropertiesResult']['Hotel'];
    
    $MyDateCarbon = Carbon::parse($date[1].'/'.$date[2].'/'.$date[0]);
    $MyDateCarbon->addDays($ResLong);

    $Finishdate = jdate('Ymd',$MyDateCarbon->timestamp,'','Asia/Tehran','en');
    $PFinishdate = jdate('Y-m-d',$MyDateCarbon->timestamp,'','Asia/Tehran','en');

    foreach($roomprices as  $key => $roomprice )
    {
      $exact_price =  $roomprice['Price'] - ( $roomprice['PriceComm'] + $roomprice['PriceCounter'] );
      $sell_price =  $exact_price + ( ($exact_price*$markup_percent)/100 );       
      $roomprices[$key]["exact_price"] = $exact_price;
      $roomprices[$key]["sell_price"] = $sell_price;

    } 
    
    return View::make('hotel.details')->with(
                                              array(
                                                  "roomprices"  =>  $roomprices, 
                                                  "HotelCod"    =>  $HotelCod,  
                                                  "HotelNam"    =>  $HotelNam, 
                                                  "CityNam"     =>  $CityNam, 
                                                  "CityCod"     =>  $CityCod, 
                                                  "PDat"        =>  $PDat,
                                                  "Hotel"       =>  $Hotel,
                                                  "ResLong"     =>  $ResLong,
                                                  'userinfo'    =>  $user,
                                                  "Pfinishdate" =>  $PFinishdate
                                              )
                                            );       
  }
));


Route::any('/hotel/reserveroom',array('as'=>'hotel-reserveroom','before'=>'logged','before'=>'csrf',
  function(){

      $HotelCod = Input::get('HotelCod');
      $StDat = Input::get('StDat');
      $ResLong = Input::get('ResLong');
      $RoomTypeCod = Input::get('RoomTypeCod');
      $NumRoom = Input::get('NumRoom');
      $HotelNam = Input::get('HotelNam');
      $CityNam = Input::get('CityNam');
      $CityCod = Input::get('CityCod');
      $real_price = Input::get('real_price');  // real room price , without mark up
      $Price = Input::get('sell_price');   
      $HotelAddress = Input::get('HotelAddress');
      $RoomTypeNam = Input::get('RoomTypeNam');
      $PDat = Input::get('PDat');
      $Pfinishdate = Input::get('Pfinishdate');
      $totallprice= $Price*$NumRoom ; 

      $Detailsvalue = 
          array(

              "HotelCode"=> $HotelCod,
              "RoomTypeCode"=>$RoomTypeCod,
              "Date" =>$StDat,
              "nights"=>$ResLong,
              "HotelName"=>$HotelNam,
              "CityName"=>$CityNam,
              "CityCode"=>$CityCod,
              "real_price"=>$real_price,
              "Price"=>$Price,
              "HotelAddress"=>$HotelAddress,
              "StartDate"=>$PDat,
              "FinishDate"=>$Pfinishdate,
              );
    $value = array
    (
      'HotelCode'=>'required|integer',
      'RoomTypeCode'=>'required|integer',
      'Date'=>'required|numeric',
      'nights'=>'required|numeric|Min:1|Max:40',
      'HotelName'=>'required|string',
      'CityName'=>'required|string',
      'CityCode'=>'required|integer',
      'real_price'=>'required|numeric',
      'Price'=>'required|numeric',
      'HotelAddress'=>'required|string',
      'StartDate'=>'required|string',
      'FinishDate'=>'required|string',
      
    );

    $validator = Validator::make($Detailsvalue, $value);
    if ($validator->fails()) 
      {
        foreach ($validator->messages()->toArray() as $key => $message)
      {
        foreach ($message as $key2 => $value) {       
            print_r("ERROR: ".$value);
            print_r("<br>");  
        }
      }
      die();
    }         

    $result= MarcopoloMgr::GetRoomPriceListDateResLong($HotelCod,$StDat,$ResLong);       
    if(!isset($result->GetRoomPriceListDateResLongResult->HotelRoomTypePriceDat))
      die("no room avalaible for this request ");
    $result= $result->GetRoomPriceListDateResLongResult->HotelRoomTypePriceDat;
    $roomprices= json_decode(json_encode($result),true);
    $markup_percent = SettingController::GetOption('markup_hotel');
    foreach($roomprices as  $roomprice )
    {
      if ($roomprice['RoomTypeCod'] == $RoomTypeCod) {
       $cost =  $roomprice['Price'] - ( $roomprice['PriceComm'] + $roomprice['PriceCounter'] );
       $sell_cost =  $cost + ( ($cost*$markup_percent)/100 );
      }
    }  
    if ($real_price != $cost ||  $Price!=$sell_cost ) {
      print_r(" roomprices have been manipulated  ");
      print_r("<br>");  
      print_r(" Returned Price: ".$Price);
            print_r("<br>");  
      print_r("Sell Price: ".$sell_cost);
            print_r("<br>"); 
            die();
    }

      $params = 
          array(

              "HotelCod"=> $HotelCod,
              "RoomTypeCod"=>$RoomTypeCod,
              "StDat" =>$StDat,
              "ResLong"=>$ResLong,
              "NumRoom"=>$NumRoom,

              );
    $ReserveNumber= MarcopoloMgr::ReserveRoom($params);
    $MyCredit = UserMgr::MyCredit();
    return View::make('hotel.reserveroom')->with(
      array(
                      'ReserveNumber'=>$ReserveNumber, 
                      "HotelCod"=>$HotelCod,  "HotelNam"=>$HotelNam, 
                      "CityCod"=>$CityCod, "CityNam"=>$CityNam, 
                      "StDat"=>$StDat, "ResLong"=>$ResLong, 
                      "RoomTypeCod"=>$RoomTypeCod, 
                      "Price"=>$Price, 
                      "totallprice"=>$totallprice,
                      "real_price"=>$real_price,
                      "HotelAddress"=>$HotelAddress,
                      "PDat"=>$PDat,
                      "Pfinishdate"=>$Pfinishdate,
                      "NumRoom"=>$NumRoom,
                      "RoomTypeNam"=>$RoomTypeNam,
                      "MyCredit"=>$MyCredit,
                      'userinfo'=>Sentry::getUser()
      ));     
   
    
}
));


Route::any('/hotel/insertinformation',array('as'=>'hotel-insertinformation','before'=>'logged','before'=>'csrf',
  function(){
      
      $NumRoom = Input::get('NumRoom');
      $NumRoomvalue = array('NumberOfRooms'=>$NumRoom);
      $value = array('NumberOfRooms'=>'required|integer');

      $validator = Validator::make($NumRoomvalue, $value);
      if ($validator->fails()) 
        {  
          foreach ($validator->messages()->toArray() as $key => $message)
        {
          foreach ($message as $key2 => $value) {
              print_r("ERROR: ".$value);
              print_r("<br>");  
          }
        }
        die();
        }

        $error ="no";
        for ($t=0; $t <$NumRoom ; $t++) { 
        $infovalue = 
          array(

              "typeroom"=> Input::get("typeroom".$t),
              "name"=>Input::get("name".$t),
              "family" =>Input::get("family".$t),
              "melicod"=>Input::get("melicod".$t),
              "birthday"=>Input::get("birthday".$t),
              );
        $value = array
        (
          'typeroom'=>'required|integer',
          'name'=>'required|string',
          'family'=>'required|string',
          'melicod'=>'required|numeric',
          'birthday'=>'required|string', 
        );

        $validator = Validator::make($infovalue, $value);
        if ($validator->fails()) 
          {
            print_r("ERROR: In Passenger ".($t+1)." Informations ");
            print_r("<br>");     
            foreach ($validator->messages()->toArray() as $key => $message)
          {
            foreach ($message as $key2 => $value) {   
                
                print_r("---->: ".$value);
                print_r("<br>");  
            }
          }     
               $error="yes";
        }                 
      }

      $HotelCod = Input::get('HotelCod');
      $StDat = Input::get('StDat');
      $ResLong = Input::get('ResLong');
      $RoomTypeCod = Input::get('RoomTypeCod');
      $NumRoom = Input::get('NumRoom');
      $HotelNam = Input::get('HotelNam');
      $CityNam = Input::get('CityNam');
      $CityCod = Input::get('CityCod');
      $Price = Input::get('Price');
      $totallprice = Input::get('totallprice');
      $real_price = Input::get('real_price');
      $ReserveNumber = Input::get('ReserveNumber');

     $Mobile = Input::get("mobile");
     $TelNo1 = Input::get("telno1");
     $TelNo2 = Input::get("telno2");
      if(!isset($TelNo2))
        $TelNo2="";
     $Address = Input::get("adress");
     $Email = Input::get("email");
     $ZipCode = Input::get("zipcode");
     if(!isset($ZipCode))
        $ZipCode="";
     $Dsc = Input::get("Dsc");
      if(!isset($Dsc))
        $Dsc="";
     $RaftAirLine = Input::get("RaftAirLine");
     $FlightNoRaft = Input::get("FlightNoRaft");
     $TimeRaft = Input::get("TimeRaft");
     $MasirRaft = Input::get("MasirRaft");
     $BargashtAirLine = Input::get("BargashtAirLine");
     $FlightNoBargasht = Input::get("FlightNoBargasht");
     $TimeBargasht = Input::get("TimeBargasht");
     $MasirBargasht = Input::get("MasirBargasht");
     $countryPhoneCode = Input::get("countryPhoneCode");
     $PayByCredit =1 ; 


      $Detailsvalue = 
          array(

              "HotelCode"=> $HotelCod,
              "RoomTypeCode"=>$RoomTypeCod,
              "Date" =>$StDat,
              "nights"=>$ResLong,
              "HotelName"=>$HotelNam,
              "CityName"=>$CityNam,
              "CityCode"=>$CityCod,
              "real_price"=>$real_price,
              "Price"=>$Price,
              "totallprice"=>$totallprice,
              "real_price"=>$real_price,
              "ReserveNumber"=>$ReserveNumber,

              "CellNumber"=>$Mobile,
              "HomeTelephoneNumber"=>$TelNo1,
              "Email"=>$Email, 
              "countryPhoneCode"=>$countryPhoneCode,
              "Address"=>$Address,
              );
    $value = array
    (
      'HotelCode'=>'required|integer',
      'RoomTypeCode'=>'required|integer',
      'Date'=>'required|numeric',
      'nights'=>'required|numeric|Min:1|Max:40',
      'HotelName'=>'required|string',
      'CityName'=>'required|string',
      'CityCode'=>'required|integer',
      'real_price'=>'required|numeric',
      'Price'=>'required|numeric',
      'totallprice'=>'required|numeric',
      'real_price'=>'required|numeric',
      'ReserveNumber'=>'required|numeric', 

      'CellNumber'=>'required|numeric',  
      'HomeTelephoneNumber'=>'required|numeric',  
      'Email'=>'required|email',  
      'countryPhoneCode'=>'required|string',  
      'Address'=>'required|string',  
    );

    $validator = Validator::make($Detailsvalue, $value);
    if ($validator->fails()) 
      {
        foreach ($validator->messages()->toArray() as $key => $message)
      {
        foreach ($message as $key2 => $value) {       
            print_r("ERROR: ".$value);
            print_r("<br>");  
        }
      }
      die();
    }         
    if ($error=="yes")
      die();


      $result= MarcopoloMgr::GetRoomPriceListDateResLong($HotelCod,$StDat,$ResLong);       
      if(!isset($result->GetRoomPriceListDateResLongResult->HotelRoomTypePriceDat))
        die("no room avalaible for this request ");
      $result= $result->GetRoomPriceListDateResLongResult->HotelRoomTypePriceDat;
      $roomprices= json_decode(json_encode($result),true);
      $markup_percent = SettingController::GetOption('markup_hotel');
      foreach($roomprices as  $roomprice )
      {
        if ($roomprice['RoomTypeCod'] == $RoomTypeCod) {
         $cost =  $roomprice['Price'] - ( $roomprice['PriceComm'] + $roomprice['PriceCounter'] );
         $sell_cost =  $cost + ( ($cost*$markup_percent)/100 );
        }
      }  
      $total_cost = $NumRoom*$sell_cost;
      if ($real_price != $cost ||  $Price!=$sell_cost || $totallprice!=$total_cost ) {
        print_r(" roomprices have been manipulated  ");
        print_r("<br>");  
        print_r(" Returned Price: ".$Price);
              print_r("<br>");  
        print_r("Sell Price: ".$sell_cost);
              print_r("<br>");
        print_r(" Returned Total price: ".$totallprice);
              print_r("<br>");  
        print_r("Sell total Price: ".$total_cost);
              print_r("<br>"); 
              die();
      }



     $i=0;
     $TypeRoomAll = Input::get("typeroom".$i);
     $NamAll = Input::get("name".$i);
     $FamilyAll = Input::get("family".$i);
     $MeliCodAll = Input::get("melicod".$i);
     $BirthDatAll = Input::get("birthday".$i);

     $date = explode('-', $BirthDatAll);
     $timestamp = strtotime($date[2].'-'.$date[1].'-'.$date[0]);
     $BirthDatAll = jdate('Ymd',$timestamp,'','Asia/Tehran','fa');

     if ($NumRoom>1) {
      for ($j=1; $j <$NumRoom ; $j++) { 
            
            $TypeRoomAll=$TypeRoomAll.",".Input::get("typeroom".$j);
            $NamAll=$NamAll.",".Input::get("name".$j);
            $FamilyAll=$FamilyAll.",".Input::get("family".$j);
            $MeliCodAll=$MeliCodAll.",".Input::get("melicod".$j);

            $birthday = Input::get("birthday".$j);
            $date = explode('-', $birthday);
            $timestamp = strtotime($date[2].'-'.$date[1].'-'.$date[0]);
            $birthday = jdate('Ymd',$timestamp,'','Asia/Tehran','fa');
            $BirthDatAll=$BirthDatAll.",".$birthday;           
      }
     }

     $params = 
        array(
            "ReserveNo"=>$ReserveNumber,
            "TypeRoomAll"=>$TypeRoomAll,
            "NamAll"=>$NamAll,
            "FamilyAll"=>$FamilyAll,
            "MeliCodAll"=>$MeliCodAll,
            "BirthDatAll"=>$BirthDatAll,
            "Mobile"=>$Mobile,
            "TelNo1"=>$TelNo1,
            "TelNo2"=>$TelNo2,
            "Address"=>$Address,
            "Email"=>$Email,
            "ZipCode"=>$ZipCode,
            "Dsc"=>$Dsc,
            "RaftAirLine"=>"",
            "FlightNoRaft"=>"",
            "TimeRaft"=>"",
            "MasirRaft"=>"",
            "BargashtAirLine"=>"",
            "FlightNoBargasht"=>"",
            "TimeBargasht"=>"",
            "MasirBargasht"=>"",
        

            );
     
    $result= MarcopoloMgr::InsertNamReserveRoomTemporary($params); 

    $result = $result->InsertNamReserveRoomTemporaryResult ; 

        if ( $result != $ReserveNumber ) { //  Else passengers informations sucsesfully registered 
             die( "ReserveNumber :".$ReserveNumber."   eror in inserting informations , error : ".$result );
                 print_r($params);
                 print_r("<br>");
                  print_r($result);  
        } 

/////////////////////////   booking and payment fucntion  ///////////////////////////////


    $typerooms=array();
    $fnames=array();
    $lnames=array();
    $melicodes=array();
    $birthdays=array();
    for ($k=0; $k <$NumRoom ; $k++) 
    { 
          $typerooms[$k]= Input::get("typeroom".$k);
          $fnames[$k]= Input::get("name".$k);
          $lnames[$k]= Input::get("family".$k);
          $melicodes[$k]= Input::get("melicod".$k);
          $birthdays[$k]= Input::get("birthday".$k);
       }

    $info = array( 
    'typerooms' =>$typerooms , 
    'fnames' =>$fnames , 
    'lnames' =>$lnames , 
    'melicodes' =>$melicodes , 
    'birthdays' =>$birthdays , 

    "ReserveNumber"=>$ReserveNumber,
    "HotelCod"=>$HotelCod,
    "HotelNam"=>$HotelNam,
    "CityCod"=>$CityCod,
    "CityNam"=>$CityNam,
    "StDat"=>$StDat,
    "ResLong"=>$ResLong,
    "RoomTypeCod"=>$RoomTypeCod,
    "NumRoom"=>$NumRoom,
    "Price"=>$Price, 
    "totallprice"=>$totallprice,
    "real_price"=>$real_price,
    "Mobile"=>$Mobile,
    "TelNo1"=>$TelNo1,
    "TelNo2"=>$TelNo2,
    "Address"=>$Address,
    "Email"=>$Email,
    "ZipCode"=>$ZipCode,
    "Dsc"=>$Dsc,
    "countryPhoneCode"=>$countryPhoneCode,
    "PayByCredit"=>$PayByCredit,
    );
      

        $HotelOrderID = MarcopoloMgr::pay($info); 
        $Order = Orders::GetFlightDetails($HotelOrderID);
    
        $Order['Hotel'] = json_decode($Order['product']['post_content'],true);

        $Order['author'] = UserMgr::GetUserAgency($Order['post_author']);
                        
         $markup_percent = SettingController::GetOption('markup_hotel');
    
    return View::make('hotel.voucher')->
        with(array('userinfo'=>Sentry::getUser(),'order'=>$Order,'markup'=>$markup_percent));

  }
));



Route::get('/hotel/finalize',array('as'=>'hotel-finalize','before'=>'logged',
  function(){
    
    Return MarcopoloMgr::ForoshRoomFromTemporary($ReserveNo); 
         
  }
));



Route::get('/hotel/getrefrencehotel',array('as'=>'hotel-getrefrencehotel','before'=>'logged',
  function(){
    
    Return MarcopoloMgr::GetRefrenceHotel($ReserveNo); 
         
  }
));



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////        extra fucntions            /////////////////////////////////////////////////

function jdate($format,$timestamp='',$none='',$time_zone='Asia/Tehran',$tr_num='fa'){

 $T_sec=0;/* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

 if($time_zone!='local')date_default_timezone_set(($time_zone=='')?'Asia/Tehran':$time_zone);
 $ts=$T_sec+(($timestamp=='' or $timestamp=='now')?time():tr_num($timestamp));
 $date=explode('_',date('H_i_j_n_O_P_s_w_Y',$ts));
 list($j_y,$j_m,$j_d)=gregorian_to_jalali($date[8],$date[3],$date[2]);
 $doy=($j_m<7)?(($j_m-1)*31)+$j_d-1:(($j_m-7)*30)+$j_d+185;
 $kab=($j_y%33%4-1==(int)($j_y%33*.05))?1:0;
 $sl=strlen($format);
 $out='';
 for($i=0; $i<$sl; $i++){
  $sub=substr($format,$i,1);
  if($sub=='\\'){
  $out.=substr($format,++$i,1);
  continue;
  }
  switch($sub){

  case'E':case'R':case'x':case'X':
  $out.='http://jdf.scr.ir';
  break;

  case'B':case'e':case'g':
  case'G':case'h':case'I':
  case'T':case'u':case'Z':
  $out.=date($sub,$ts);
  break;

  case'a':
  $out.=($date[0]<12)?'ق.ظ':'ب.ظ';
  break;

  case'A':
  $out.=($date[0]<12)?'قبل از ظهر':'بعد از ظهر';
  break;

  case'b':
  $out.=(int)($j_m/3.1)+1;
  break;

  case'c':
  $out.=$j_y.'/'.$j_m.'/'.$j_d.' ،'.$date[0].':'.$date[1].':'.$date[6].' '.$date[5];
  break;

  case'C':
  $out.=(int)(($j_y+99)/100);
  break;

  case'd':
  $out.=($j_d<10)?'0'.$j_d:$j_d;
  break;

  case'D':
  $out.=jdate_words(array('kh'=>$date[7]),' ');
  break;

  case'f':
  $out.=jdate_words(array('ff'=>$j_m),' ');
  break;

  case'F':
  $out.=jdate_words(array('mm'=>$j_m),' ');
  break;

  case'H':
  $out.=$date[0];
  break;

  case'i':
  $out.=$date[1];
  break;

  case'j':
  $out.=$j_d;
  break;

  case'J':
  $out.=jdate_words(array('rr'=>$j_d),' ');
  break;

  case'k';
  $out.=tr_num(100-(int)($doy/($kab+365)*1000)/10,$tr_num);
  break;

  case'K':
  $out.=tr_num((int)($doy/($kab+365)*1000)/10,$tr_num);
  break;

  case'l':
  $out.=jdate_words(array('rh'=>$date[7]),' ');
  break;

  case'L':
  $out.=$kab;
  break;

  case'm':
  $out.=($j_m>9)?$j_m:'0'.$j_m;
  break;

  case'M':
  $out.=jdate_words(array('km'=>$j_m),' ');
  break;

  case'n':
  $out.=$j_m;
  break;

  case'N':
  $out.=$date[7]+1;
  break;

  case'o':
  $jdw=($date[7]==6)?0:$date[7]+1;
  $dny=364+$kab-$doy;
  $out.=($jdw>($doy+3) and $doy<3)?$j_y-1:(((3-$dny)>$jdw and $dny<3)?$j_y+1:$j_y);
  break;

  case'O':
  $out.=$date[4];
  break;

  case'p':
  $out.=jdate_words(array('mb'=>$j_m),' ');
  break;

  case'P':
  $out.=$date[5];
  break;

  case'q':
  $out.=jdate_words(array('sh'=>$j_y),' ');
  break;

  case'Q':
  $out.=$kab+364-$doy;
  break;

  case'r':
  $key=jdate_words(array('rh'=>$date[7],'mm'=>$j_m));
  $out.=$date[0].':'.$date[1].':'.$date[6].' '.$date[4]
 .' '.$key['rh'].'، '.$j_d.' '.$key['mm'].' '.$j_y;
  break;

  case's':
  $out.=$date[6];
  break;

  case'S':
  $out.='ام';
  break;

  case't':
  $out.=($j_m!=12)?(31-(int)($j_m/6.5)):($kab+29);
  break;

  case'U':
  $out.=$ts;
  break;

  case'v':
   $out.=jdate_words(array('ss'=>substr($j_y,2,2)),' ');
  break;

  case'V':
  $out.=jdate_words(array('ss'=>$j_y),' ');
  break;

  case'w':
  $out.=($date[7]==6)?0:$date[7]+1;
  break;

  case'W':
  $avs=(($date[7]==6)?0:$date[7]+1)-($doy%7);
  if($avs<0)$avs+=7;
  $num=(int)(($doy+$avs)/7);
  if($avs<4){
   $num++;
  }elseif($num<1){
   $num=($avs==4 or $avs==(($j_y%33%4-2==(int)($j_y%33*.05))?5:4))?53:52;
  }
  $aks=$avs+$kab;
  if($aks==7)$aks=0;
  $out.=(($kab+363-$doy)<$aks and $aks<3)?'01':(($num<10)?'0'.$num:$num);
  break;

  case'y':
  $out.=substr($j_y,2,2);
  break;

  case'Y':
  $out.=$j_y;
  break;

  case'z':
  $out.=$doy;
  break;

  default:$out.=$sub;
  }
 }
 return($tr_num!='en')?tr_num($out,'fa','.'):$out;
}

/*  F */
function jstrftime($format,$timestamp='',$none='',$time_zone='Asia/Tehran',$tr_num='fa'){

 $T_sec=0;/* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

 if($time_zone!='local')date_default_timezone_set(($time_zone=='')?'Asia/Tehran':$time_zone);
 $ts=$T_sec+(($timestamp=='' or $timestamp=='now')?time():tr_num($timestamp));
 $date=explode('_',date('h_H_i_j_n_s_w_Y',$ts));
 list($j_y,$j_m,$j_d)=gregorian_to_jalali($date[7],$date[4],$date[3]);
 $doy=($j_m<7)?(($j_m-1)*31)+$j_d-1:(($j_m-7)*30)+$j_d+185;
 $kab=($j_y%33%4-1==(int)($j_y%33*.05))?1:0;
 $sl=strlen($format);
 $out='';
 for($i=0; $i<$sl; $i++){
  $sub=substr($format,$i,1);
  if($sub=='%'){
  $sub=substr($format,++$i,1);
  }else{
  $out.=$sub;
  continue;
  }
  switch($sub){

  /* Day */
  case'a':
  $out.=jdate_words(array('kh'=>$date[6]),' ');
  break;

  case'A':
  $out.=jdate_words(array('rh'=>$date[6]),' ');
  break;

  case'd':
  $out.=($j_d<10)?'0'.$j_d:$j_d;
  break;

  case'e':
  $out.=($j_d<10)?' '.$j_d:$j_d;
  break;

  case'j':
  $out.=str_pad($doy+1,3,0,STR_PAD_LEFT);
  break;

  case'u':
  $out.=$date[6]+1;
  break;

  case'w':
  $out.=($date[6]==6)?0:$date[6]+1;
  break;

  /* Week */
  case'U':
  $avs=(($date[6]<5)?$date[6]+2:$date[6]-5)-($doy%7);
  if($avs<0)$avs+=7;
  $num=(int)(($doy+$avs)/7)+1;
  if($avs>3 or $avs==1)$num--;
  $out.=($num<10)?'0'.$num:$num;
  break;

  case'V':
  $avs=(($date[6]==6)?0:$date[6]+1)-($doy%7);
  if($avs<0)$avs+=7;
  $num=(int)(($doy+$avs)/7);
  if($avs<4){
   $num++;
  }elseif($num<1){
   $num=($avs==4 or $avs==(($j_y%33%4-2==(int)($j_y%33*.05))?5:4))?53:52;
  }
  $aks=$avs+$kab;
  if($aks==7)$aks=0;
  $out.=(($kab+363-$doy)<$aks and $aks<3)?'01':(($num<10)?'0'.$num:$num);
  break;

  case'W':
  $avs=(($date[6]==6)?0:$date[6]+1)-($doy%7);
  if($avs<0)$avs+=7;
  $num=(int)(($doy+$avs)/7)+1;
  if($avs>3)$num--;
  $out.=($num<10)?'0'.$num:$num;
  break;

  /* Month */
  case'b':
  case'h':
  $out.=jdate_words(array('km'=>$j_m),' ');
  break;

  case'B':
  $out.=jdate_words(array('mm'=>$j_m),' ');
  break;

  case'm':
  $out.=($j_m>9)?$j_m:'0'.$j_m;
  break;

  /* Year */
  case'C':
  $out.=substr($j_y,0,2);
  break;

  case'g':
  $jdw=($date[6]==6)?0:$date[6]+1;
  $dny=364+$kab-$doy;
  $out.=substr(($jdw>($doy+3) and $doy<3)?$j_y-1:(((3-$dny)>$jdw and $dny<3)?$j_y+1:$j_y),2,2);
  break;  

  case'G':
  $jdw=($date[6]==6)?0:$date[6]+1;
  $dny=364+$kab-$doy;
  $out.=($jdw>($doy+3) and $doy<3)?$j_y-1:(((3-$dny)>$jdw and $dny<3)?$j_y+1:$j_y);
  break;

  case'y':
  $out.=substr($j_y,2,2);
  break;

  case'Y':
  $out.=$j_y;
  break;

  /* Time */
  case'H':
  $out.=$date[1];
  break;

  case'I':
  $out.=$date[0];
  break;

  case'l':
  $out.=($date[0]>9)?$date[0]:' '.(int)$date[0];
  break;

  case'M':
  $out.=$date[2];
  break;

  case'p':
  $out.=($date[1]<12)?'قبل از ظهر':'بعد از ظهر';
  break;

  case'P':
  $out.=($date[1]<12)?'ق.ظ':'ب.ظ';
  break;

  case'r':
  $out.=$date[0].':'.$date[2].':'.$date[5].' '.(($date[1]<12)?'قبل از ظهر':'بعد از ظهر');
  break;

  case'R':
  $out.=$date[1].':'.$date[2];
  break;

  case'S':
  $out.=$date[5];
  break;

  case'T':
  $out.=$date[1].':'.$date[2].':'.$date[5];
  break;

  case'X':
  $out.=$date[0].':'.$date[2].':'.$date[5];
  break;

  case'z':
  $out.=date('O',$ts);
  break;

  case'Z':
  $out.=date('T',$ts);
  break;

  /* Time and Date Stamps */
  case'c':
  $key=jdate_words(array('rh'=>$date[6],'mm'=>$j_m));
  $out.=$date[1].':'.$date[2].':'.$date[5].' '.date('P',$ts)
 .' '.$key['rh'].'، '.$j_d.' '.$key['mm'].' '.$j_y;
  break;

  case'D':
  $out.=substr($j_y,2,2).'/'.(($j_m>9)?$j_m:'0'.$j_m).'/'.(($j_d<10)?'0'.$j_d:$j_d);
  break;

  case'F':
  $out.=$j_y.'-'.(($j_m>9)?$j_m:'0'.$j_m).'-'.(($j_d<10)?'0'.$j_d:$j_d);
  break;

  case's':
  $out.=$ts;
  break;

  case'x':
  $out.=substr($j_y,2,2).'/'.(($j_m>9)?$j_m:'0'.$j_m).'/'.(($j_d<10)?'0'.$j_d:$j_d);
  break;

  /* Miscellaneous */
  case'n':
  $out.="\n";
  break;

  case't':
  $out.="\t";
  break;

  case'%':
  $out.='%';
  break;

  default:$out.=$sub;
  }
 }
 return($tr_num!='en')?tr_num($out,'fa','.'):$out;
}

/*  F */
function jmktime($h='',$m='',$s='',$jm='',$jd='',$jy='',$is_dst=-1){
 $h=tr_num($h); $m=tr_num($m); $s=tr_num($s); $jm=tr_num($jm); $jd=tr_num($jd); $jy=tr_num($jy);
 if($h=='' and $m=='' and $s=='' and $jm=='' and $jd=='' and $jy==''){
  return mktime();
 }else{
  list($year,$month,$day)=jalali_to_gregorian($jy,$jm,$jd);
  return mktime($h,$m,$s,$month,$day,$year,$is_dst);
 }
}

/*  F */
function jgetdate($timestamp='',$none='',$tz='Asia/Tehran',$tn='en'){
 $ts=($timestamp=='')?time():tr_num($timestamp);
 $jdate=explode('_',jdate('F_G_i_j_l_n_s_w_Y_z',$ts,'',$tz,$tn));
 return array(
  'seconds'=>tr_num((int)tr_num($jdate[6]),$tn),
  'minutes'=>tr_num((int)tr_num($jdate[2]),$tn),
  'hours'=>$jdate[1],
  'mday'=>$jdate[3],
  'wday'=>$jdate[7],
  'mon'=>$jdate[5],
  'year'=>$jdate[8],
  'yday'=>$jdate[9],
  'weekday'=>$jdate[4],
  'month'=>$jdate[0],
  0=>tr_num($ts,$tn)
 );
}

/*  F */
function jcheckdate($jm,$jd,$jy){
 $jm=tr_num($jm); $jd=tr_num($jd); $jy=tr_num($jy);
 $l_d=($jm==12)?(($jy%33%4-1==(int)($jy%33*.05))?30:29):31-(int)($jm/6.5);
 return($jm>0 and $jd>0 and $jy>0 and $jm<13 and $jd<=$l_d)?true:false;
}

/*  F */
function tr_num($str,$mod='en',$mf='٫'){
 $num_a=array('0','1','2','3','4','5','6','7','8','9','.');
 $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹',$mf);
 return($mod=='fa')?str_replace($num_a,$key_a,$str):str_replace($key_a,$num_a,$str);
}

/*  F */
function jdate_words($array,$mod=''){
 foreach($array as $type=>$num){
  $num=(int)tr_num($num);
  switch($type){

  case'ss':
  $sl=strlen($num);
  $xy3=substr($num,2-$sl,1);
  $h3=$h34=$h4='';
  if($xy3==1){
   $p34='';
   $k34=array('ده','یازده','دوازده','سیزده','چهارده','پانزده','شانزده','هفده','هجده','نوزده');
   $h34=$k34[substr($num,2-$sl,2)-10];
  }else{
   $xy4=substr($num,3-$sl,1);
   $p34=($xy3==0 or $xy4==0)?'':' و ';
   $k3=array('','','بیست','سی','چهل','پنجاه','شصت','هفتاد','هشتاد','نود');
   $h3=$k3[$xy3];
   $k4=array('','یک','دو','سه','چهار','پنج','شش','هفت','هشت','نه');
   $h4=$k4[$xy4];
  }
  $array[$type]=(($num>99)?str_ireplace(array('12','13','14','19','20')
 ,array('هزار و دویست','هزار و سیصد','هزار و چهارصد','هزار و نهصد','دوهزار')
 ,substr($num,0,2)).((substr($num,2,2)=='00')?'':' و '):'').$h3.$p34.$h34.$h4;
  break;

  case'mm':
  $key=array
  ('فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');
  $array[$type]=$key[$num-1];
  break;

  case'rr':
  $key=array('یک','دو','سه','چهار','پنج','شش','هفت','هشت','نه','ده','یازده','دوازده','سیزده'
 ,'چهارده','پانزده','شانزده','هفده','هجده','نوزده','بیست','بیست و یک','بیست و دو','بیست و سه'
 ,'بیست و چهار','بیست و پنج','بیست و شش','بیست و هفت','بیست و هشت','بیست و نه','سی','سی و یک');
  $array[$type]=$key[$num-1];
  break;

  case'rh':
  $key=array('یکشنبه','دوشنبه','سه شنبه','چهارشنبه','پنجشنبه','جمعه','شنبه');
  $array[$type]=$key[$num];
  break;

  case'sh':
  $key=array('مار','اسب','گوسفند','میمون','مرغ','سگ','خوک','موش','گاو','پلنگ','خرگوش','نهنگ');
  $array[$type]=$key[$num%12];
  break;

  case'mb':
  $key=array('حمل','ثور','جوزا','سرطان','اسد','سنبله','میزان','عقرب','قوس','جدی','دلو','حوت');
  $array[$type]=$key[$num-1];
  break;

  case'ff':
  $key=array('بهار','تابستان','پاییز','زمستان');
  $array[$type]=$key[(int)($num/3.1)];
  break;

  case'km':
  $key=array('فر','ار','خر','تی‍','مر','شه‍','مه‍','آب‍','آذ','دی','به‍','اس‍');
  $array[$type]=$key[$num-1];
  break;

  case'kh':
  $key=array('ی','د','س','چ','پ','ج','ش');
  $array[$type]=$key[$num];
  break;

  default:$array[$type]=$num;
  }
 }
 return($mod=='')?$array:implode($mod,$array);
}

/** Gregorian & Jalali (Hijri_Shamsi,Solar) date converter Functions
Copyright(C)2015 JDF.SCR.IR : [ http://jdf.scr.ir/jdf ] version 2.60
--------------------------------------------------------------------
1461 = 365*4 + 4/4   &  146097 = 365*400 + 400/4 - 400/100 + 400/400
12053 = 365*33 + 32/4    &    36524 = 365*100 + 100/4 - 100/100   */

/*  F */
function gregorian_to_jalali($gy,$gm,$gd,$mod=''){
  $gy=tr_num($gy); $gm=tr_num($gm); $gd=tr_num($gd);/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
 $g_d_m=array(0,31,59,90,120,151,181,212,243,273,304,334);
 $jy=($gy<=1600)?0:979;
 $gy-=($gy<=1600)?621:1600;
 $gy2=($gm>2)?($gy+1):$gy;
 $days=(365*$gy) +((int)(($gy2+3)/4)) -((int)(($gy2+99)/100)) 
+((int)(($gy2+399)/400)) -80 +$gd +$g_d_m[$gm-1];
 $jy+=33*((int)($days/12053)); 
 $days%=12053;
 $jy+=4*((int)($days/1461));
 $days%=1461;
 $jy+=(int)(($days-1)/365);
 if($days > 365)$days=($days-1)%365;
 $jm=($days < 186)?1+(int)($days/31):7+(int)(($days-186)/30);
 $jd=1+(($days < 186)?($days%31):(($days-186)%30));
 return($mod=='')?array($jy,$jm,$jd):$jy.$mod.$jm.$mod.$jd;
}

/*  F */
function jalali_to_gregorian($jy,$jm,$jd,$mod=''){
  $jy=tr_num($jy); $jm=tr_num($jm); $jd=tr_num($jd);/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
 $gy=($jy<=979)?621:1600;
 $jy-=($jy<=979)?0:979;
 $days=(365*$jy) +(((int)($jy/33))*8) +((int)((($jy%33)+3)/4)) 
+78 +$jd +(($jm<7)?($jm-1)*31:(($jm-7)*30)+186);
 $gy+=400*((int)($days/146097));
 $days%=146097;
 if($days > 36524){
  $gy+=100*((int)(--$days/36524));
  $days%=36524;
  if($days >= 365)$days++;
 }
 $gy+=4*((int)(($days)/1461));
 $days%=1461;
 $gy+=(int)(($days-1)/365);
 if($days > 365)$days=($days-1)%365;
 $gd=$days+1;
 foreach(array(0,31,(($gy%4==0 and $gy%100!=0) or ($gy%400==0))?29:28 
,31,30,31,30,31,31,30,31,30,31) as $gm=>$v){
  if($gd<=$v)break;
  $gd-=$v;
 }
 return($mod=='')?array($gy,$gm,$gd):$gy.$mod.$gm.$mod.$gd; 
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



Route::get('/hotel/getallhotelLlst',array('as'=>'hotel-getallhotelLlst','before'=>'logged',
  function($HotelCod){
    
    Return MarcopoloMgr::GetAllHotelList(); 
         
  }
));

Route::get('/hotel/gethotelproperties/{HotelCod}',array('as'=>'hotel-gethotelproperties','before'=>'logged',
  function($HotelCod){
    
    Return MarcopoloMgr::GetHotelProperties($HotelCod); 
         
  }
));


Route::get('/hotel/getroomList/{HotelCod}',array('as'=>'hotel-getroomList','before'=>'logged',
  function($HotelCod){
    
    Return MarcopoloMgr::GetRoomList($HotelCod); 
         
  }
));


Route::get('/hotel/onedayroomprice/{HotelCod}/{$dat}',array('as'=>'hotel-onedayroomprice','before'=>'logged',
  function($HotelCod,$Dat){
    
    Return MarcopoloMgr::GetRoomPriceListOneDate($HotelCod,$Dat); 
         
  }
));



//////////////////////////////////////////// data entry ////////////////////////////////////////////


Route::get('/hotel/data/',array('as'=>'hotel-data-entry-index','before'=>'logged',
  function(){
    $Posts = MarcopoloMgr::findAllPosts();
    return View::make('hotel.data.index')->with(array('userinfo'=>Sentry::getUser(),'paginator'=>$Posts,'status'=>Input::get('status','All')));
  }
));

Route::get('/hotel/add',array('as'=>'hotel-data-add','before'=>'logged',
  function(){
    $countries = array();
    $c = MarcoCountryDB::all();
    foreach ($c as $key => $value) {
      $countries [ $value [ "CountryCod" ] ] = $value [ "CountryNam" ];
    }
    return View::make('hotel.data.add')->with(array('userinfo'=>Sentry::getUser(),'countries'=>$countries));
  }
));

// ajax get cities
Route::any('/hotel/get/province/',array('as'=>'hotel-data-province','before'=>'logged',
  function(){
    $countryID = Input::get("Country_Code");
    $province = MarcoProvinceDB::where("CountryCod","=",$countryID)->get(array("CityNam","CityCod"));
    $output = array();
    foreach ($province as $key => $value) {
      $output [ $key ] [ "id" ] = trim($value['CityCod']);
      $output [ $key ] [ "name" ] = trim($value['CityNam']);
    }
    return Response::json($output);
  }
));

// ajax get hotels
Route::any('/hotel/get/hotels/',array('as'=>'hotel-data-hotels','before'=>'logged',
  function(){
    $cityID = Input::get("City_Code");
    $hotels = MarcoHotelDB::where("CityCod","=",$cityID)->get();
    $output = array();
    foreach ($hotels as $key => $value) {
      $output [ $key ] = $value;
      $output [ $key ] [ "id" ] = trim($value['HotelCod']);
      $output [ $key ] [ "name" ] = trim($value['HotelNam']);
    }
    return Response::json($output);
  }
));

// ajax get specific hotel
Route::any('/hotel/get/hotel/',array('as'=>'hotel-data-hotel','before'=>'logged',
  function(){
    $hotelCod = Input::get("Hotel_Code");
    $hotel = MarcoHotelDB::where("hotelCod","=",$hotelCod)->get()->first()->toArray();
    return Response::json($hotel);
  }
));

// add new data
Route::post('/hotel/add',array('as'=>'hotel-add-form','before'=>'logged', 'uses' => 'MarcopoloMgr@AddNewHotelData'));
// edit hotel row
Route::get('/hotel/edit/{id}',array('as'=>'hotel-edit-form','before'=>'logged', 'uses' => 'MarcopoloMgr@showHotelData'))->where('id', '[0-9]+');
// update hotel row
Route::post('/hotel/edit/{id}',array('as'=>'hotel-update-form','before'=>'logged', 'uses' => 'MarcopoloMgr@updateHotelData'))->where('id', '[0-9]+');