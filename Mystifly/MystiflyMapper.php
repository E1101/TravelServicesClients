<?php
namespace Tsp\Mystifly;


class MystiflyMapper {

    protected $template = [
        'status'=>false,
        'data'=>[],
        'Errors'=>[],
    ];

    function __invoke($rawBody)
    {
//        $template['status'] = $rawBody['SessionStatus'];
//        $template['Errors'] = $rawBody['Errors'];
//        $template['data']   = $this->array_except($rawBody,array('SessionStatus','Errors','Target'));
        return $rawBody;
    }

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
}
