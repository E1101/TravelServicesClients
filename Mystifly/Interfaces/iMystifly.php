<?php
namespace Tsp\Mystifly\Interfaces;

interface iMystifly
{
    /**
     * generate session base on mystifly session generator
     *
     * @return iResponse
     */
    function createSession();

    /**
     * get list of available flight from mystifly webservice
     *
     * @param array $inputs
     * 
     * @return iResponse
     */
    function airLowFareSearch($inputs);
}
