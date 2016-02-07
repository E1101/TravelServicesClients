<?php
namespace Tsp\Mystifly\ApiClient;

use Tsp\Mystifly\Interfaces\iStorage;

class FileStorage implements iStorage
{
    protected $storageFilePath;

    /** @var array */
    protected $data;


    /**
     * FileStorage constructor.
     * @param null|string $filePath
     */
    function __construct($filePath = null)
    {
        if ($filePath === null)
            $filePath = __DIR__.'/../.storage.php';

        $this->storageFilePath = $filePath;
    }


    /**
     * Get Data By Name
     *
     * @param null|string $key
     * @param null|mixed  $default
     *
     * @return mixed
     */
    function get($key = null, $default = null)
    {
        if( ! $this->_isStorageFileLoaded() )
            $this->_loadDataFromFile();

        return (is_null($key)) ? $this->data
            :(
                (array_key_exists($key, $this->data))
                    ? $this->data [ $key ]
                    : $default
            );
    }

    /**
     * Set Data
     *
     * @param array $options      [key=>val]
     * @param bool $updateStorage save data to storage
     *
     * @return $this
     * @internal param mixed $storage
     */
    function set($options , $updateStorage = false)
    {
        foreach($options as $key => $value){
            $this->data [ $key ] = $value;
        }

        if($updateStorage)
            $this->save();

        return $this;
    }

    /**
     * Save Current Data To Storage
     *
     * @return $this
     */
    function save()
    {
        file_put_contents($this->storageFilePath
            , '<?php return '
              . str_replace(['array (',')'],['[',']'],var_export($this->data, true))
              . ';'
        );

        return $this;
    }


    // ...

    /**
     * Load Data From File In Memory
     * @return $this
     */
    protected function _loadDataFromFile()
    {
        if (!file_exists($this->storageFilePath))
            throw new \RuntimeException(sprintf(
                'file (%s) not found.'
                , $this->storageFilePath
            ));

        $this->data = require $this->storageFilePath;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function _isStorageFileLoaded()
    {
        if(is_null($this->data))
            return false;

        return true;
    }

}