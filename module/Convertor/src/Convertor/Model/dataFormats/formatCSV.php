<?php

namespace Convertor\Model\dataFormats;

class formatCSV implements dataFormatsInterface
{
    /**
     * Method guesses type of new line coding in data string - LF of CR+LF.
     *
     * @param string $str
     *
     * @return string Line delimiter
     */
    private static function guessStringDelimiter($str)
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
     * Method reads CSV data into internal storage.
     *
     * @param array  &$arr Array to store data
     * @param string $str  Input data string
     */
    public static function readData(&$arr, $str)
    {
        $stringDelimiter = self::guessStringDelimiter($str);
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
                $arr[$i-1][$titles[$j]] = $values[$j];
            }
        }
    }

    /**
     * Methods gets Content-Type header value for CSV file.
     *
     * @return string
     */
    public static function getCT()
    {
        return 'text/csv';
    }

    /**
     * Method returns internal data in CSV format.
     *
     * @return string CSV data
     */
    public static function getData(&$arrData)
    {
        $str = '';
        $titles = array();
        foreach ($arrData as $arr) {
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
        foreach ($arrData as $arr) {
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
}
