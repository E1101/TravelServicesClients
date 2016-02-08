<?php
namespace Tsp\Mystifly\Interfaces;

interface iStorage
{
    /**
     * Get Data By Name
     *
     * @param null|string $keys
     * @param null|mixed  $default
     *
     * @return mixed
     */
    function get($keys = null, $default = null);

    /**
     * Set Data
     *
     * @param array $options      [key=>val]
     * @param bool $updateStorage save data to storage
     *
     * @return $this
     * @internal param mixed $storage
     */
    function set($options , $updateStorage = false);

    /**
     * Save Current Data To Storage
     *
     * @return $this
     */
    function save();


}