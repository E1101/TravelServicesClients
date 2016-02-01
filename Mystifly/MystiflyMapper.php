<?php
namespace Tsp\Mystifly;


class MystiflyMapper
{
    protected $template = [
        'status'=>false,
        'data'=>[],
        'Errors'=>[],
    ];

    /**
     * @param $array
     * @param array $excludeKeys
     * @return mixed
     */
    function array_except($array, Array $excludeKeys){
        foreach($excludeKeys as $key){
            unset($array[$key]);
        }
        return $array;
    }

    /**
     * @param $response
     * @return mixed
     */
    function makeResponseCreateSession($response){
        $template['status'] = $response['CreateSessionResult']['SessionStatus'];
        $template['Errors'] = $response['CreateSessionResult']['Errors'];
        $template['data']   = $this->array_except($response['CreateSessionResult'],array('SessionStatus','Errors','Target'));
        return $template;
    }

    /**
     * @param $response
     * @return mixed
     */
    function makeResponseAirLowFareSearch($response){
        $template['status'] = $response['AirLowFareSearchResult']['Success'];
        $template['Errors'] = $response['AirLowFareSearchResult']['Errors'];
        $template['data']   = $this->array_except($response['AirLowFareSearchResult']['PricedItineraries'],array('Success','Errors','Target'));
//        $template['data']   = $this->normalizeAirLowFareSearch($template['data']);
        return $template;
    }

}