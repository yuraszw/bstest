<?php

namespace Convertor\Model\dataFormats;

class formatJSON implements dataFormatsInterface
{
    /**
     * Method reads JSON data into internal stotage.
     *
     * @param array  &$arr Array to store data
     * @param string $str  Input data string
     */
    public static function readData(&$arr, $str)
    {
        $data = json_decode($str);
        if (is_null($data)) {
            throw new \Exception('Error parsing JSON');
        }
        $i = 0;
        foreach ($data as $val) {
            foreach ($val as $key => $v) {
                $arr[$i][$key] = $v;
            }
            $i++;
        }
    }

    /**
     * Methods gets Content-Type header value for JSON file.
     *
     * @return string Content-Type
     */
    public static function getCT()
    {
        return 'application/json';
    }

    /**
     * Method returns internal data in JSON format.
     *
     * @return string JSON data
     */
    public static function getData(&$arrData)
    {
        return json_encode($arrData);
    }
}
