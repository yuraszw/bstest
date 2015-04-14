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
     * Method guesses type of new line coding in data string - LF of CR+LF.
     *
     * @param string $str
     *
     * @return string Line delimiter
     */
    private function guessStringDelimiter($str)
    {
        $i = 0;
        $j = 0;
        $pos = -1;
        while (($pos = strpos($str, "\n", $pos+1)) !== false) {
            $i++;
        }
        $pos = -1;
        while (($pos = strpos($str, "\r\n", $pos+1)) !== false) {
            $j++;
        }

        return ($i == $j) ? "\r\n" : "\n";
    }

    /**
     * Call to undefined method. This happens when call to undefined read* or get* method.
     */
    public function __call($method, $args)
    {
        throw new \Exception("Ivalid method called ($method). Perhaps, unsupported format?");
    }

    /**
     * Method reads CSV data into internal stotage.
     *
     * @param string $str
     */
    public function readCSV($str)
    {
        $stringDelimiter = $this->guessStringDelimiter($str);
        $strArray = explode($stringDelimiter, $str);
        $no = count($strArray);
        $titles = str_getcsv($strArray[0]);
        $cols = count($titles);
        if ($cols<2) {
            throw new \Exception('Error parsing CSV 1');
        }
        for ($i = 1;$i<$no;$i++) {
            $values = str_getcsv($strArray[$i]);
            if (count($values) == 1) {
                continue;
            }
            if (count($values) != $cols) {
                throw new \Exception('Error parsing CSV 2');
            }
            for ($j = 0;$j<$cols;$j++) {
                if ($values[$j] == '') {
                    continue;
                }
                $this->data[$i-1][$titles[$j]] = $values[$j];
            }
        }
    }

    /**
     * Methods gets Content-Type header value for CSV file.
     */
    public function getCSV_CT()
    {
        return 'text/csv';
    }

    /**
     * Method returns internal data in CSV format.
     *
     * @return string CSV data
     */
    public function getCSV()
    {
        $str = '';
        $titles = array();
        foreach ($this->data as $arr) {
            foreach ($arr as $key => $vals) {
                $titles[$key] = true;
            }
        }
        foreach ($titles as $key => $val) {
            if (strpos($key, ',') === false) {
                $str .= $key.',';
            } else {
                $str .= '"'.$key.'",';
            }
        }
        $str[strlen($str)-1] = "\n";
        foreach ($this->data as $arr) {
            foreach ($titles as $key => $val) {
                if (isset($arr[$key])) {
                    if (strpos($val, ',') === false) {
                        $str .= $arr[$key].',';
                    } else {
                        $str .= '"'.$arr[$key].'",';
                    }
                } else {
                    $str .= ',';
                }
            }
            $str[strlen($str)-1] = "\n";
        }

        return $str;
    }

    /**
     * Method reads XML data into internal storage using SimpleXMLObject.
     *
     * @param string $str
     */
    public function readXML($str)
    {
        $data = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOWARNING|LIBXML_NOERROR);
        if ($data === false) {
            throw new \Exception('Error parsing XML');
        }
        $i = 0;
        foreach ($data as $val) {
            foreach ($val->attributes() as $key => $v) {
                $this->data[$i][$key] = $v->__toString();
            }
            foreach ($val as $key => $v) {
                $this->data[$i][$key] = $v->__toString();
            }
            $i++;
        }
    }

    /**
     * Methods gets Content-Type header value for XML file.
     */
    public function getXML_CT()
    {
        return 'application/xml';
    }

    /**
     * Method returns internal data in XML format.
     *
     * @return string XML data
     */
    public function getXML()
    {
        $xml = new \SimpleXMLElement('<dataset/>');
        foreach ($this->data as $arr) {
            $sxml = $xml->addChild('data');
            foreach ($arr as $key => $val) {
                $sxml->addChild($key, $val);
            }
        }

        return $xml->asXML();
    }

    /**
     * Method reads JSON data into internal stotage using SimpleXMLObject.
     *
     * @param string $str
     */
    public function readJSON($str)
    {
        $data = json_decode($str);
        if (is_null($data)) {
            throw new \Exception('Error parsing JSON');
        }
        $i = 0;
        foreach ($data as $val) {
            foreach ($val as $key => $v) {
                $this->data[$i][$key] = $v;
            }
            $i++;
        }
    }

    /**
     * Methods gets Content-Type header value for JSON file.
     */
    public function getJSON_CT()
    {
        return 'application/json';
    }

    /**
     * Method returns internal data in JSON format.
     *
     * @return string JSON data
     */
    public function getJSON()
    {
        $str = json_encode($this->data);

        return $str;
    }

    /**
     * Universal method to guess input file type by contents.
     * Not ready yet.
     */
    public function readANY($str, $exclude = '')
    {
        $funcs = get_class_methods(__CLASS__);
        $readMethods = array();
        foreach ($funcs as $fname) {
            if (strpos($fname, 'read') !== 0) {
                continue;
            }
            if (($fname == __FUNCTION__) || ($fname == $exclude)) {
                continue;
            }
            $readMethods[] = $fname;
        }
        $no = count($readMethods);
        for ($i = 0;$i<$no;$i++) {
            try {
                $this->{$readMethods[$i]}($str);
            } catch (\Exception $e) {
                echo 'GOT:'.$e->getMessage().PHP_EOL;
            }
        }
        //print_r($readMethods);
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
     public function getData()
     {
         return $this->data;
     }
     /**
      * Method sets internal data - used for testing.
      *
      * @parameter array Array of assotiative arrays
      */
     public function setData($arr)
     {
         $this->data = $arr;
     }
}
