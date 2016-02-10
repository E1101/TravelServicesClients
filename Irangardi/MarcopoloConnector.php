<?php
/**
* main class of functions
*/
namespace Libraries;

class MarcopoloConnector 
{
	protected $wsdllink ;
	protected $OprCod ;
	protected $CustCod ;
	protected $PID ;
	protected $Mojavez ;
	

	
	function __construct($wsdllink,$OprCod,$CustCod,$PID,$Mojavez)
	{
		$this->wsdllink = $wsdllink ;
		$this->OprCod = $OprCod ;
		$this->CustCod = $CustCod ;
		$this->PID = $PID ;
		$this->Mojavez = $Mojavez ;
	}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////        main fucntions           ///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




		public  function GetHotelListCity($CityCod)
	{
	   
		
		try 
	    {

	       $client = new \SoapClient($this->wsdllink,array('connection'=>'close'));
	    
	        $params = 
	        array(

	            "CityCod" => $CityCod ,
	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            
	            );
	      
	        
	        $result = $client->GetHotelListCity($params);
	         
	        return $result;       
	       

	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }		
	 	
	}	

	public  function GetRoomPriceListDateResLong($HotelCod,$Dat,$ResLong) 
	{
		
			
		try 
	    {


	       $client = new \SoapClient($this->wsdllink,array('connection'=>'close'));
	    
	        $params = 
	        array(

	            "HotelCod"=>$HotelCod ,
	            "RoomTypeCod"=>"0",  //   0 => all rooms
	            "ResLong"=>$ResLong,
	           	"Dat" =>$Dat,

	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	        

	        $result = $client->GetRoomPriceListDateResLong($params);
	                
	      return $result;

	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
	}	

	public  function ReserveRoom(array $input) 
	{
		
 		try 
	    {

	          $client = new \SoapClient($this->wsdllink,array('connection'=>'close'));
	    
	        $params = 
	        array(

	            "HotelCod"=> $input['HotelCod'],
	            "RoomTypeCod"=>$input['RoomTypeCod'],
	            "StDat" =>$input['StDat'],
	            "ResLong"=>$input['ResLong'],
	            "NumRoom"=>$input['NumRoom'],
	         
	            
	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	        
	        $result = $client->ReserveRoom($params);
	                
	      return $result->ReserveRoomResult;
	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
	}	

	public  function InsertNamReserveRoomTemporary(array $input)
	{

			$ReserveNo = $input['ReserveNo'];
			$TypeRoomAll = $input['TypeRoomAll'];
			$NamAll = $input['NamAll'];
			$FamilyAll = $input['FamilyAll'];
			$MeliCodAll = $input['MeliCodAll'];
			$BirthDatAll = $input['BirthDatAll'];
			$Mobile = $input['Mobile'];
			$TelNo1 = $input['TelNo1'];
			$TelNo2 = $input['TelNo2'];
			$Address = $input['Address'];
			$Email = $input['Email'];
			$ZipCode = $input['ZipCode'];
			$Dsc = $input['Dsc'];
			$RaftAirLine = $input['RaftAirLine'];
			$FlightNoRaft = $input['FlightNoRaft'];
			$TimeRaft = $input['TimeRaft'];
			$MasirRaft = $input['MasirRaft'];
			$BargashtAirLine = $input['BargashtAirLine'];
			$FlightNoBargasht = $input['FlightNoBargasht'];
			$TimeBargasht = $input['TimeBargasht'];
			$MasirBargasht = $input['MasirBargasht'];
	

		try 
	    {

			$client = new \SoapClient($this->wsdllink,array('connection'=>'close'));
	    	    
	        $params = 
	        array(
	            "ReserveNo"=>$input['ReserveNo'],
	            "TypeRoomAll"=> $input['TypeRoomAll'],
	            "NamAll"=>$input['NamAll'],
	            "FamilyAll"=>$input['FamilyAll'],
	            "MeliCodAll"=>$input['MeliCodAll'],
	            "BirthDatAll"=>$input['BirthDatAll'],
	            "Mobile"=>$input['Mobile'],
	            
	            "TelNo1"=>$input['TelNo1'],
	            "TelNo2"=>$input['TelNo2'],
	            "Address"=>$input['Address'],
	            "Email"=>$input['Email'],
	            "ZipCode"=>$input['ZipCode'],
	            "Dsc"=>$input['Dsc'],
	            "RaftAirLine"=>$input['RaftAirLine'],
	            "FlightNoRaft"=>$input['FlightNoRaft'],
	            "TimeRaft"=>$input['TimeRaft'],
	            "MasirRaft"=>$input['MasirRaft'],
	            "BargashtAirLine"=>$input['BargashtAirLine'],
	            "FlightNoBargasht"=>$input['FlightNoBargasht'],
	            "TimeBargasht"=>$input['TimeBargasht'],
	            "MasirBargasht"=>$input['MasirBargasht'],

	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );

	        $result = $client->InsertNamReserveRoomTemporary($params);
	                
	        return $result;

	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }		
	}


	public  function ForoshRoomFromTemporary($ReserveNo)
	{
		try 
	    {

			$client = new \SoapClient($this->wsdllink,array('connection'=>'close'));
	    
	    
	        $params = 
	        array(
	            "ReserveNo"=>$ReserveNo,
	 			
	 		    "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	        
	        $result = $client->ForoshRoomFromTemporary($params);
	                
	        return $result;

	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
	 		
	}		


	public  function GetRefrenceHotel($ReserveNo)
	{
		try 
	    {

	        
			$client = new \SoapClient($this->wsdllink,array('connection'=>'close'));
	    
	    
	        $params = 
	        array(
	            "ReserveNo"=>$ReserveNo,
	 			
	 		    "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	      
	        
	        $result = $client->GetRefrenceHotel($params);
	                
	        return $result;

	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
 		
	}	


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////        extra fucntions            /////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public  function GetAllHotelList()
	{
		
	 		try 
	    {

			$client = new \SoapClient($this->wsdllink,array('connection'=>'close'));	    
	        $params = 
	        array(

	       
	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	        
	        $result = $client->GetAllHotelList($params);
	                
	  	    return $result;


	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
	}	


	public  function GetHotelProperties($HotelCod) 
	{
		
	 		try 
	    {

			$client = new \SoapClient($this->wsdllink,array('connection'=>'close'));	    	

	        $params = 
	        array(

	            "HotelCod"=>$HotelCod,
	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	        
	        $result = $client->GetHotelProperties($params);
	                
	        return $result;


	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
	}	


	public function GetRoomList($HotelCod) 
	{
		
	 	 try 
	    {

			$client = new \SoapClient($this->wsdllink,array('connection'=>'close'));	   

	        $params = 
	        array(
			    "HotelCod"=>$HotelCod,
	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	        
	        $result = $client->GetRoomList($params);
	                
	        return $result;


	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
	}	


	public static function GetRoomPriceListOneDate($HotelCod,$Dat) 
	{

			
 		try 
	    {

			$client = new \SoapClient($this->wsdllink,array('connection'=>'close'));
	    
	        $params = 
	        array(

	        	"HotelCod"=>$HotelCod ,
	            "RoomTypeCod"=>"0",  //   0 => all rooms
	           	"Dat" =>$Dat,
	           
	            "OprCod" =>$this->OprCod ,
	            "CustCod" => $this->CustCod,
	            "PID" =>$this->PID ,
	            "Mojavez" =>$this->Mojavez
	            );
	      
	        
	        $result = $client->GetRoomPriceListOneDate($params);
	                
	        return $result;

	    } 
	    catch (SoapFault $fault)
	    {
	                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	    }
	}	
}

