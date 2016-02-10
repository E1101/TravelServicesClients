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

    /**
     * revalidate selected flight
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function airRevalidate($inputs);

    /**
     * book selected flight with passengers information
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function bookFlight($inputs);

    /**
     * cancel given book reference id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function cancelBooking($inputs);

    /**
     * get selected flight fare rules ( new implementation )
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function fareRules1_1($inputs);

    /**
     * order given booking reference id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function ticketOrder($inputs);

    /**
     * get flight detail from given ticket id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function tripDetails($inputs);

    /**
     * add some note to selected booking reference id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function addBookingNotes($inputs);

    /**
     * get book details
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function airBookingData($inputs);

    /**
     * get specific category message queues
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function messageQueues($inputs);

    /**
     * remove selected queue messages
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function removeMessageQueues($inputs);

    /**
     *
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function multiAirRevalidate($inputs);
}
