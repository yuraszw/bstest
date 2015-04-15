<?php

/**
 * Class dataHolder definition.
 *
 * Object containes data in internal representation. Class read*($str) methods
 * import data presented by string argument into internal format. Methods
 * get*() return internal data as string in corresponding format.
 *
 * @author Yuriy Sizonenko <yuraszw@gmail.com>
 *
 * @version 1.0
 */

namespace Convertor\Model;

/**
 * dataHolder class definition.
 */
class dataHolder
{
    /**
     * Array of arrays, each presenting set of key-value pair.
     */
    private $data;
    /**
     * Class constructor, just initialize internal data storage.
     */
    public function __construct()
    {
        $this->data = array();
    }

    /**
     * Check class for definition. All supported formats should have formatXXX
     * class defined. Class should implement interface
     * Convertor\Model\dataFormats\dataFormatsInterface.
     * On error generates corresponding Exception.
     *
     * @throws Exception
     */
    private function checkClass($classname)
    {
        if (!class_exists($classname)) {
            spl_autoload_call($classname);
            if (!class_exists($classname)) {
                throw new \Exception('No corresponding class found. Unsupported format?');
            }
        }
        if (!in_array('Convertor\Model\dataFormats\dataFormatsInterface',
        class_implements($classname))) {
            throw new \Exception('Class should implement dataFormats interface');
        }
    }

    /**
     * Method reads data into internal storage using corresponding class method.
     *
     * @param string $fileContents Contents of input file
     * @param string $ext          Type of input file (extension)
     */
    public function readData($fileContents, $ext)
    {
        $classname = 'Convertor\Model\dataFormats\format'.strtoupper($ext);
        $this->checkClass($classname);
        $classname::readData($this->data, $fileContents);
    }

    /**
     * Method converts data from internal storage to specified format
     * using method of corresponding class.
     *
     * @param string $ext Type of output file (extension)
     */
    public function getData($ext)
    {
        $classname = 'Convertor\Model\dataFormats\format'.strtoupper($ext);
        $this->checkClass($classname);

        return $classname::getData($this->data);
    }

    /**
     * Method returns Content-Type string for specified file type.
     *
     * @param string $ext Type of output file (extension)
     */
    public function getCT($ext)
    {
        $classname = 'Convertor\Model\dataFormats\format'.strtoupper($ext);

        return $classname::getCT();
    }

    /**
     * Present internal data, used for debugging.
     */
    public function report()
    {
        print_r($this->data);
    }

     /**
      * Method returns internal data presentation - used for testing.
      *
      * @return array Array of assotiative arrays
      */
     public function getInternalData()
     {
         return $this->data;
     }
     /**
      * Method sets internal data - used for testing.
      *
      * @parameter array Array of assotiative arrays
      */
     public function setInternalData($arr)
     {
         $this->data = $arr;
     }
}
