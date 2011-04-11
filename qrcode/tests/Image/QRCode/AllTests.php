<?php
/**
 * QRCode_AllTests
 * 
 * @category  Images
 * @package   Image_QRCode
 * @author    Rich Sage <rich.sage@gmail.com> 
 * @copyright 2009 Rich Sage
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1 
 * @link      http://code.google.com/p/pearqrcode
 */

require_once 'PHPUnit/Framework.php';
require_once 'Image/QRCode.php';
require_once 'Image/QRCodeTest.php';

/**
 * All tests for Image_QRCode
 * 
 * @category  Images
 * @package   Image_QRCode
 * @author    Rich Sage <rich.sage@gmail.com> 
 * @copyright 2009 Rich Sage
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link      http://code.google.com/p/pearqrcode
 */
class QRCode_AllTests
{
    /**
     * suite 
     * 
     * @return PHPUnit_Framework_TestSuite
     */
    static public function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Image_QRCode Unit Test Suite');
        $suite->addTestSuite('Image_QRCodeTest');
        return $suite;
    }
}

?>
