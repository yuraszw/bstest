<?php

namespace Convertor\Model\dataFormats;

class formatXML implements dataFormatsInterface
{
    /**
     * Method reads XML data into internal stotage.
     *
     * @param array  &$arr Array to store data
     * @param string $str  Input data string
     */
    public static function readData(&$arr, $str)
    {
        $data = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOWARNING|LIBXML_NOERROR);
        if ($data === false) {
            throw new \Exception('Error parsing XML');
        }
        $i = 0;
        foreach ($data as $val) {
            foreach ($val->attributes() as $key => $v) {
                $arr[$i][$key] = $v->__toString();
            }
            foreach ($val as $key => $v) {
                $arr[$i][$key] = $v->__toString();
            }
            $i++;
        }
    }

    /**
     * Methods gets Content-Type header value for XML file.
     *
     * @return string
     */
    public static function getCT()
    {
        return 'application/xml';
    }

    /**
     * Method returns supplied data in XML format.
     *
     * @return string XML data
     */
    public static function getData(&$arrData)
    {
        $xml = new \SimpleXMLElement('<dataset/>');
        foreach ($arrData as $arr) {
            $sxml = $xml->addChild('data');
            foreach ($arr as $key => $val) {
                $sxml->addChild($key, $val);
            }
        }

        return $xml->asXML();
    }
}
