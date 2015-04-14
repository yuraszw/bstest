<?php

namespace ConvertorTest\Model;

use Convertor\Model\dataHolder;
use PHPUnit_Framework_TestCase;

class ConvertorTest extends PHPUnit_Framework_TestCase
{
    public function testConvertorInitialState()
    {
        $dh = new dataHolder();
        $this->assertEquals(array(), $dh->getData());
    }

    /**
     * Simple CSV import test.
     */
    public function testCSV1()
    {
        $str = <<<Done
a,b,c,d
1,2,3,4
5,6,7,8
Done;
        $dh = new dataHolder();
        $dh->readCSV($str);
        $res = $dh->getData();
        $this->assertEquals($res, array(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
            array('a' => 5, 'b' => 6, 'c' => 7, 'd' => 8), ));
    }

    /**
     * Simple CSV import test - some values are missed.
     */
    public function testCSV2()
    {
        $str = <<<Done
a,b,c,d
1,,3,4
5,6,,8
Done;
        $dh = new dataHolder();
        $dh->readCSV($str);
        $res = $dh->getData();
        $this->assertEquals($res, array(array('a' => 1, 'c' => 3, 'd' => 4),
            array('a' => 5, 'b' => 6, 'd' => 8), ));
    }

    /**
     * Simple CSV import test - some data contains commas(,).
     */
    public function testCSV3()
    {
        $str = <<<Done
a,b,c,d
1,"2,a",3,4
5,6,"7,b",8
Done;
        $dh = new dataHolder();
        $dh->readCSV($str);
        $res = $dh->getData();
        $this->assertEquals($res, array(array('a' => 1, 'b' => '2,a', 'c' => 3, 'd' => 4),
            array('a' => 5, 'b' => 6, 'c' => '7,b', 'd' => 8), ));
    }

    /**
     * Simple CSV import test - some keys contains commas(,).
     */
    public function testCSV4()
    {
        $str = <<<Done
a,"b,3",c,d
1,2,3,4
5,6,7,8
Done;
        $dh = new dataHolder();
        $dh->readCSV($str);
        $res = $dh->getData();
        $this->assertEquals($res, array(array('a' => 1, 'b,3' => 2, 'c' => 3, 'd' => 4),
            array('a' => 5, 'b,3' => 6, 'c' => 7, 'd' => 8), ));
    }

    /**
     * Expect exception - too many fields in one string.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Error parsing CSV
     */
    public function testCSVInvalidInput1()
    {
        $str = <<<Done
a,b,c,d
1,2,3,4,
5,6,7,8
Done;
        $dh = new dataHolder();
        $dh->readCSV($str);
    }
    /**
     * Expect exception - too few fields in one string.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Error parsing CSV
     */
    public function testCSVInvalidInput2()
    {
        $str = <<<Done
a,b,c,d
1,2,34
5,6,7,8
Done;
        $dh = new dataHolder();
        $dh->readCSV($str);
    }


    /**
     * Simple XML import test.
     */
    public function testXML()
    {
        $str = <<<Done
<?xml version="1.0"?>
<dataset>
 <data><a>1</a><b>2</b><c>3</c><d>4</d></data>
 <data><a>5</a><d>8</d></data>
 <data><a>9</a><b>10</b><d>12</d></data>
</dataset>
Done;
        $dh = new dataHolder();
        $dh->readXML($str);
        $res = $dh->getData();
        $this->assertEquals($res, array(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
            array('a' => 5, 'd' => 8), array('a' => 9, 'b' => 10, 'd' => 12) ));
    }

    /**
     * Expect exception.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Error parsing XML
     */
    public function testXMLInvalidInput()
    {
        $str = <<<Done
<?xml version="1.0"?>
<dataset>
 <data><a>1</a><b>2</b><c>3</c><d>4</d></data>
 <data><a>5</a><d>8
 <data><a>9</a><b>10</b><d>12</d></data>
</dataset>
Done;
        $dh = new dataHolder();
        $dh->readXML($str);
    }


    /**
     * Simple JSON import test.
     */
    public function testJSON()
    {
        $str = <<<Done
[{"a":"1","b":"2","c":"3","d":"4"},{"a":"5","d":"8"},{"a":"9","b":"10","d":"12"}]
Done;
        $dh = new dataHolder();
        $dh->readJSON($str);
        $res = $dh->getData();
        $this->assertEquals($res, array(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
            array('a' => 5, 'd' => 8), array('a' => 9, 'b' => 10, 'd' => 12) ));
    }

    /**
     * Expect exception - invalid JSON.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Error parsing JSON
     */
    public function testJSONInvalidInput()
    {
        $str = <<<Done
[{"a":"1","b":"2","c":"3","d":"4"},{"a":"5","d":"8",{"a":"9","b":"10","d":"12"}]
Done;
        $dh = new dataHolder();
        $dh->readJSON($str);
    }


    /**
     * Expect Exception - trying to call unknows type parser
     *
     * @expectedException Exception
     * @expectedExceptionMessage Ivalid method called
     */
    public function testInvalidType()
    {
	$str = 'Some string';
	$dh = new dataHolder();
	$dh->readUNDEFINED($str);
    }


    /**
     * Export CSV file
     */

    public function testCSVExport()
    {
        $str = <<<Done
a,b,c,d
1,2,3,4
5,6,7,8

Done;
        $dh = new dataHolder();
	$dh->setData(array(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
            array('a' => 5, 'b' => 6, 'c' => 7, 'd' => 8) ));

        $res = $dh->getCSV();
        $this->assertEquals($res, $str);
    }

    /**
     * Export JSON file
     */

    public function testJSONExport()
    {
        $str = <<<Done
[{"a":1,"b":2,"c":3,"d":4},{"a":5,"b":6,"c":7,"d":8}]
Done;
        $dh = new dataHolder();
	$dh->setData(array(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
            array('a' => 5, 'b' => 6, 'c' => 7, 'd' => 8) ));

        $res = $dh->getJSON();
        $this->assertEquals($res, $str);
    }

    /**
     * Export XML file
     */

    public function testXMLExport()
    {
        $str = <<<Done
<?xml version="1.0"?>
<dataset><data><a>1</a><b>2</b><c>3</c><d>4</d></data><data><a>5</a><b>6</b><c>7</c><d>8</d></data></dataset>

Done;
        $dh = new dataHolder();
	$dh->setData(array(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
            array('a' => 5, 'b' => 6, 'c' => 7, 'd' => 8) ));

        $res = $dh->getXML();
        $this->assertEquals($res, $str);
    }



}
