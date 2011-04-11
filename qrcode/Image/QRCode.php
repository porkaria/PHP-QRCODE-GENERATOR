<?php

/**
 * QRCode - QR 2D barcode generator
 *
 * QRCode - QR 2D barcode generator
 *
 * PHP version 5
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library in the file LICENSE.LGPL; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307 USA
 *
 * From the original code by Y.Swetake:
 * THIS SOFTWARE IS PROVIDED BY Y.Swetake ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL Y.Swetake OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)  HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Images
 * @package   Image_QRCode
 * @author    Y.Swetake <swe@venus.dti.ne.jp>
 * @author    Rich Sage <rich.sage@gmail.com>
 * @copyright 2009 Y.Swetake 
 * @copyright 2009 R.Sage
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   SVN: $Id$
 * @link      http://code.google.com/p/pearqrcode/
 */

/**
 * Exception handling
 */

require_once 'QRCode/Exception.php';

/**
 * QRCode
 *
 * @category Images
 * @package  Image_QRCode
 * @author   Y.Swetake <swe@venus.dti.ne.jp>
 * @author   Rich Sage <rich.sage@gmail.com>
 * @license  http://www.gnu.org/licenses/lgpl-2.1.txt GNU LGPL
 * @version  Release: 0.1
 * @link     http://code.google.com/p/pearqrcode/
 */

class Image_QRCode
{

    /**
     * path to the data directory
     *
     * @var string
     */
    protected $path;

    /**
     * path to the image directory
     *
     * @var string
     */
    protected $image_path;

    /**
     * upper limit for version
     *
     * @var integer
     */
    protected $version_ul;

    /**
     * string/data to encode
     *
     * @var string
     */
    protected $data_string;

    /**
     * output type
     * - return: return raw PHP GD image object
     * - display: output to browser
     *
     * @var string
     */
    protected $output_type;

    /**
     * image_type
     * - png/jpeg
     *
     * @var string
     */
    protected $image_type;

    /**
     * base_image
     *
     * @var gd_image
     */
    protected $base_image;

    /**
     * output image
     *
     * @var gd_image
     */
    protected $output_image;

    /**
     * total number of data bits
     *
     * @var integer
     */
    protected $total_data_bits;

    /**
     * Error correction indicator
     *
     * @var string
     */
    protected $error_correct;

    /**
     * lookup table for alphanumeric characters
     * Note: the '$' index is in single quotes as otherwise
     * PHP tries to parse it as a variable (see PEAR bug #17321)
     *
     * @var array
     */
    protected $alphanumeric_hash = array(
        "0"=>0,"1"=>1,"2"=>2,"3"=>3,"4"=>4,
        "5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"A"=>10,"B"=>11,"C"=>12,"D"=>13,"E"=>14,
        "F"=>15,"G"=>16,"H"=>17,"I"=>18,"J"=>19,"K"=>20,"L"=>21,"M"=>22,"N"=>23,
        "O"=>24,"P"=>25,"Q"=>26,"R"=>27,"S"=>28,"T"=>29,"U"=>30,"V"=>31,
        "W"=>32,"X"=>33,"Y"=>34,"Z"=>35," "=>36,'$'=>37,"%"=>38,"*"=>39,
        "+"=>40,"-"=>41,"."=>42,"/"=>43,":"=>44
    );

    /**
     * Maximum data bits lookup
     *
     * @var array
     */
    protected $max_data_bits_array = array(
        0,128,224,352,512,688,864,992,1232,1456,1728,
        2032,2320,2672,2920,3320,3624,4056,4504,5016,5352,
        5712,6256,6880,7312,8000,8496,9024,9544,10136,10984,
        11640,12328,13048,13800,14496,15312,15936,16816,17728,18672,

        152,272,440,640,864,1088,1248,1552,1856,2192,
        2592,2960,3424,3688,4184,4712,5176,5768,6360,6888,
        7456,8048,8752,9392,10208,10960,11744,12248,13048,13880,
        14744,15640,16568,17528,18448,19472,20528,21616,22496,23648,

        72,128,208,288,368,480,528,688,800,976,
        1120,1264,1440,1576,1784,2024,2264,2504,2728,3080,
        3248,3536,3712,4112,4304,4768,5024,5288,5608,5960,
        6344,6760,7208,7688,7888,8432,8768,9136,9776,10208,

        104,176,272,384,496,608,704,880,1056,1232,
        1440,1648,1952,2088,2360,2600,2936,3176,3560,3880,
        4096,4544,4912,5312,5744,6032,6464,6968,7288,7880,
        8264,8920,9368,9848,10288,10832,11408,12016,12656,13328
    );
    
    /**
     * Maximum number of codewords, dependant on barcode version
     * 
     * @var array
     */
    protected $max_codewords_array =  array(
        0,26,44,70,100,134,172,196,242,
        292,346,404,466,532,581,655,733,815,901,991,1085,1156,
        1258,1364,1474,1588,1706,1828,1921,2051,2185,2323,2465,
        2611,2761,2876,3034,3196,3362,3532,3706
    );    

    /**
     * Formatting data for the final barcode
     *
     * @var array
     */
    protected $format_array = array(
        "101010000010010","101000100100101",
        "101111001111100","101101101001011","100010111111001","100000011001110",
        "100111110010111","100101010100000","111011111000100","111001011110011",
        "111110110101010","111100010011101","110011000101111","110001100011000",
        "110110001000001","110100101110110","001011010001001","001001110111110",
        "001110011100111","001100111010000","000011101100010","000001001010101",
        "000110100001100","000100000111011","011010101011111","011000001101000",
        "011111100110001","011101000000110","010010010110100","010000110000011",
        "010111011011010","010101111101101"
    );
    
    /**
     * Maximum number of data bits
     *
     * @var integer
     */
    protected $max_data_bits;

    /**
     * Size of the module
     *
     * @var integer
     */
    protected $module_size;

    /**
     * Data values
     *
     * @var array
     */
    protected $data_value;

    /**
     * Incremental counter, pointer into the data arrays
     *
     * @var integer
     */
    protected $data_counter;

    /**
     * Data bits array
     *
     * @var array
     */
    protected $data_bits;

    /**
     * Codeword pointer, incremental
     *
     * @var integer
     */
    protected $codeword_num_counter_value;

    /**
     * Codeword details
     *
     * @var array
     */
    protected $codeword_num_plus;

    /**
     * RS-ECC codewords
     *
     * @var string
     */
    protected $rs_ecc_codewords;

    /**
     * Codewords calculated prior to matrix generation
     *
     * @var array
     */
    protected $codewords;

    /**
     * Maximum number of data codewords
     *
     * @var integer
     */
    protected $max_data_codewords;

    /**
     * Final matrix details for plotting barcode
     *
     * @var array
     */
    protected $matrix_content;

    /**
     * Matrix X array
     *
     * @var array
     */
    protected $matrix_x_array;

    /**
     * Matrix Y array
     *
     * @var array
     */
    protected $matrix_y_array;

    /**
     * Masking array - used in final matrix generation
     *
     * @var array
     */
    protected $mask_array;

    /**
     * RS Block order array
     *
     * @var array
     */
    protected $rs_block_order;

    /**
     * RS Calculation table array
     *
     * @var array
     */
    protected $rs_cal_table_array;
    
    /**
     * Byte number counter
     * 
     * @var integer
     */
    protected $byte_num;

    /**
     * Class constructor
     * 
     * @param array $options An array of options for the class
     */
    public function __construct($options = array())
    {
        $this->path = "@data_dir@" . DIRECTORY_SEPARATOR . "Image_QRCode" .
            DIRECTORY_SEPARATOR . "data";
        if ("@data_dir@" == "@" . "data_dir@") {
            // development path 
            $this->path = dirname(__FILE__) . "/../data";
        }

        $this->image_path = "@data_dir@" . DIRECTORY_SEPARATOR . "Image_QRCode" .
            DIRECTORY_SEPARATOR . "image";
        if ("@data_dir@" == "@" . "data_dir@") {
            // development path
            $this->image_path = dirname(__FILE__) . "/../image";
        }

        $this->version_ul = 40;

        $this->data_string = "";

        $this->output_type = "display";
        $this->image_type = "png";

        $this->total_data_bits = 0;
        $this->error_correct = "M";
        $this->module_size = 0;

        $this->matrix_content = array();
        
        // handle options
        if (array_key_exists("version", $options)) {
            $this->setVersion($options["version"]);
        }
        if (array_key_exists("path", $options)) {
            $this->path = $options["path"];
        }
        if (array_key_exists("image_path", $options)) {
            $this->image_path = $options["image_path"];
        }
    }
    
    /**
     * Sets the data to encode
     *
     * @param string $str the data to encode into the barcode
     *
     * @return void
     * @throws Image_QRCode_Exception
     */
    protected function setData($str)
    {
        $str = trim($str);

        if (strlen($str) == 0) {
            throw new Image_QRCode_Exception("Data cannot be empty");
        }
        $this->data_string = $str;
        $this->data_length = strlen($str);
    }

    /**
     * Sets the error correction level.
     * One of:
     * L: 7% error level
     * M: 15% error level
     * Q: 25% error level
     * H: 30% error level
     *
     * @param char $e the error level to use
     *
     * @return void
     * @throws Image_QRCode_Exception
     */
    protected function setErrorCorrect($e)
    {
        $e = strtoupper(trim($e));
        if (!in_array($e, array("L", "M", "Q", "H"))) {
            throw new Image_QRCode_Exception("Error correction level not supported");
        }
        $this->error_correct = $e;
    }

    /**
     * Sets the default module size
     * Defaults are PNG: 4, JPEG: 8
     *
     * @param integer $size the module size to use
     *
     * @return void
     * @throws Image_QRCode_Exception
     */
    protected function setModuleSize($size)
    {
        $size = intval($size);
        if ($size < 1) {
            throw new Image_QRCode_Exception("Module size is invalid");
        }
        $this->module_size = $size;
    }

    /**
     * Sets the version number to use
     * (between 1 and 40)
     *
     * Version 1 is 21*21 matrix
     * and 4 modules increases whenever 1 version increases.
     * So version 40 is 177*177 matrix.
     *
     * @param integer $v the version number to use for this barcode
     *
     * @return void
     * @throws Image_QRCode_Exception
     */
    protected function setVersion($v)
    {
        $v = intval($v);
        if ($v < 1 || $v > $this->version_ul) {
            throw new Image_QRCode_Exception("Version is invalid");
        }
        $this->version = $v;
    }

    /**
     * Defines the return image type
     * (from 'jpeg', or 'png')
     *
     * Default is PNG format
     *
     * @param string $t the image type to use
     *
     * @return void
     * @throws Image_QRCode_Exception
     */
    protected function setImageType($t)
    {
        $t = strtolower($t);
        if (!in_array($t, array("jpeg", "png"))) {
            throw new Image_QRCode_Exception("Image format not supported");
        }
        $this->image_type = $t;
    }

    /**
     * Sets what to do once the code has been generated
     * ('display' or 'return')
     *
     * Default is to display the image, complete with headers.
     *
     * @param string $t what to do with the image once it's been created
     *
     * @return void
     * @throws Image_QRCode_Exception
     */
    protected function setOutputType($t)
    {
        $t = strtolower($t);
        if (!in_array($t, array("display", "return"))) {
            throw new Image_QRCode_Exception("Output type not supported");
        }
        $this->output_type = $t;
    }

    /**
     * Sets the data to append to the code (experimental)
     *
     * @param array $data an array of elements (n, m, parity, originaldata)
     *
     * @return void
     */
    protected function setStructureAppend($data = array())
    {
        if (!array_key_exists("n", $data)
            && !array_key_exists("m", $data)
            && !array_key_exists("parity", $data)
            && !array_key_exists("originaldata", $data)
        ) {
            throw new Image_QRCode_Exception("Appended structure data is not valid");
        }

        $this->structureappend_n = $data["n"];
        $this->structureappend_m = $data["m"];
        $this->structureappend_parity = $data["parity"];
        $this->structureappend_originaldata = $data["originaldata"];
    }

    /**
     * Performs all necessary calculations and returns/outputs an image
     * as defined by the configuration
     * 
     * @param string $data    the string/data to encode into the barcode
     * @param array  $options an array of options for the barcode
     * @param array  $append  additional data to append (experimental)
     *
     * @return resource
     * @throws Image_QRCode_Exception
     */
    public function makeCode($data = "", $options = array(), $append = array())
    {
        // apply any passed options
        if (array_key_exists("image_type", $options)) {
            $this->setImageType($options["image_type"]);
        }
        if (array_key_exists("output_type", $options)) {
            $this->setOutputType($options["output_type"]);
        }
        if (array_key_exists("error_correct", $options)) {
            $this->setErrorCorrect($options["error_correct"]);
        }
        if (array_key_exists("module_size", $options)) {
            $this->setModuleSize($options["module_size"]);
        }

        // set our data value
        $this->setData($data);
        
        // if we have an extra set of data to append, set this now
        if (!empty($append)) {
            $this->setStructureAppend($append);
        }

        // some checks to start with
        if ($this->module_size <= 0) {
            $this->module_size = 4;
        }

        $this->data_counter = 0;
        
        // apply parity and m/n structure append data
        $this->applyStructureAppend();
        
        $this->data_bits[$this->data_counter] = 4;

        // Determine the encoding mode, based on the input data
        $this->determineEncoding();

        if (@$this->data_bits[$this->data_counter] > 0) {
            $this->data_counter++;
        }
        $i = 0;
        $this->total_data_bits = 0;
        while ($i < $this->data_counter) {
            $this->total_data_bits += $this->data_bits[$i];
            $i++;
        }

        $ecc_character_hash = array(
            "L"=>"1",
            "l"=>"1",
            "M"=>"0",
            "m"=>"0",
            "Q"=>"3",
            "q"=>"3",
            "H"=>"2",
            "h"=>"2"
        );

        $this->ec = @$ecc_character_hash[$this->error_correct];

        if (!$this->ec) {
            $this->ec = 0;
        }

        // Calculate the version of the code we're generating
        $this->checkVersion();

        $this->total_data_bits += $this->codeword_num_plus[$this->version];
        $this->data_bits[$this->codeword_num_counter_value]
            += $this->codeword_num_plus[$this->version];

        $max_codewords = $this->max_codewords_array[$this->version];
        $max_modules_1side = 17 + ($this->version << 2);

        $matrix_remain_bit = array(
            0,0,7,7,7,7,7,0,0,0,0,0,0,0,3,3,3,3,3,3,3,
            4,4,4,4,4,4,4,3,3,3,3,3,3,3,0,0,0,0,0,0
        );

        /* Create our base clean matrix data, ready for our own data */
        $this->emptyMatrix($max_modules_1side);
        
        /* ECC  -this is the heart of the codeword calculations */
        $format_info = $this->performECCOperation(
            $matrix_remain_bit,
            $max_codewords
        );

        /* Attach calculated codeword data to the matrix */
        $this->attachCodewordData($max_codewords, $matrix_remain_bit);

        /* Mask selection */
        $mask_number = $this->maskSelection($max_modules_1side);
        $mask_content = 1 << $mask_number;

        /* Calculate our format information */
        $this->calculateFormatInformation($format_info, $mask_number);

        /* Create base image based on calculated size */
        $mib = $this->createBaseImage($max_modules_1side);
         
        // Add actual matrix data to the base image
        $this->addMatrixToImage($max_modules_1side, $mask_content);

        // create output image, saved in $this->output_image
        $this->createOutputImage($mib);

        // what are we doing with the created output image?
        if ($this->output_type == "return") {
            return $this->output_image;
        } else {
            switch ($this->image_type)
            {
            case "jpeg":
                header("Content-type: image/jpeg");
                imagejpeg($this->output_image);
                break;

            case "png":
                header("Content-type: image/png");
                imagepng($this->output_image);
                break;
            }
        }
    }

    /**
     * Performs version checking/calculation
     *
     * @return void
     * @throws Image_QRCode_Exception
     */
    protected function checkVersion()
    {
        if (!isset($this->version) || !is_numeric($this->version)) {
            $this->version = 0;
        }

        if (!$this->version) {
            $i = 1 + 40 * $this->ec;
            $j = $i + 39;
            $this->version = 1;
            while ($i <= $j) {
                $cw = $this->codeword_num_plus[$this->version];

                $max = $this->max_data_bits_array[$i];
                $tdb = $this->total_data_bits + $cw;

                if ($max >= $tdb) {
                    $this->max_data_bits = $this->max_data_bits_array[$i];
                    break;
                }
                $i++;
                $this->version++;
            }
        } else {
            $b = $this->max_data_bits_array[$this->version + 40 * $this->ec];
            $this->max_data_bits = $b;
        }
        if ($this->version > $this->version_ul) {
            throw new Image_QRCode_Exception("Version number is too large");
        }
    }

    /**
     * Determines the encoding needed for the data provided
     * 
     * @return void
     */
    protected function determineEncoding()
    {
        if (preg_match("/[^0-9]/", $this->data_string) != 0) {

            $expr = "/[^0-9A-Z \$\*\%\+\.\/\:\-]/";
            if (preg_match($expr, $this->data_string) != 0) {

                /* 8-bit byte mode */

                $this->codeword_num_plus = array(
                    0,0,0,0,0,0,0,0,0,0,
                    8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,
                    8,8,8,8,8,8,8,8,8,8,8,8,8,8
                );

                $this->data_value[$this->data_counter] = 4;
                $this->data_counter++;
                $this->data_value[$this->data_counter] = $this->data_length;
                $this->data_bits[$this->data_counter] = 8;     /* version 1-9 */
                $this->codeword_num_counter_value = $this->data_counter;

                $this->data_counter++;
                $i = 0;
                while ($i < $this->data_length) {
                    $this->data_value[$this->data_counter] = ord(
                        substr(
                            $this->data_string,
                            $i,
                            1
                        )
                    );
                    $this->data_bits[$this->data_counter] = 8;
                    $this->data_counter++;
                    $i++;
                }
            } else {

                /* Alphanumeric mode */

                $this->codeword_num_plus = array(
                    0,0,0,0,0,0,0,0,0,0,
                    2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,
                    4,4,4,4,4,4,4,4,4,4,4,4,4,4
                );

                $this->data_value[$this->data_counter] = 2;
                $this->data_counter++;
                $this->data_value[$this->data_counter] = $this->data_length;
                $this->data_bits[$this->data_counter] = 9;    /* version 1-9 */
                $this->codeword_num_counter_value = $this->data_counter;

                $i = 0;
                $this->data_counter++;
                while ($i < $this->data_length) {
                    if (($i % 2) == 0) {
                        $c = substr($this->data_string, $i, 1);
                        $h = $this->alphanumeric_hash[$c];
                        $this->data_value[$this->data_counter] = $h;
                        $this->data_bits[$this->data_counter] = 6;
                    } else {
                        $c = substr($this->data_string, $i, 1);
                        $h = $this->alphanumeric_hash[$c];

                        $this->data_value[$this->data_counter]
                            = $this->data_value[$this->data_counter] * 45 + $h;
                        $this->data_bits[$this->data_counter] = 11;
                        $this->data_counter++;
                    }
                    $i++;
                }
            }
        } else {

            /* Numeric mode */

            $this->codeword_num_plus = array(
                0,0,0,0,0,0,0,0,0,0,
                2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,
                4,4,4,4,4,4,4,4,4,4,4,4,4,4
            );

            $this->data_value[$this->data_counter] = 1;
            $this->data_counter++;
            $this->data_value[$this->data_counter] = $this->data_length;
            $this->data_bits[$this->data_counter] = 10;     /* #version 1-9 */
            $this->codeword_num_counter_value = $this->data_counter;

            $i = 0;
            $this->data_counter++;
            while ($i < $this->data_length) {
                if (($i % 3) == 0) {
                    $this->data_value[$this->data_counter]
                        = substr($this->data_string, $i, 1);
                    $this->data_bits[$this->data_counter] = 4;
                } else {
                    $c = substr($this->data_string, $i, 1);
                    $this->data_value[$this->data_counter]
                        = $this->data_value[$this->data_counter] * 10 + $c;

                    if (($i % 3) == 1) {
                        $this->data_bits[$this->data_counter] = 7;
                    } else {
                        $this->data_bits[$this->data_counter] = 10;
                        $this->data_counter++;
                    }
                }
                $i++;
            }
        }
    }

    /**
     * Performs the error correction and code generation operations
     * 
     * @param array   $matrix_remain_bit array of remaining bits for data matrix
     * @param integer $max_codewords     number of maximum codewords
     * 
     * @return array
     */
    protected function performECCOperation($matrix_remain_bit, $max_codewords)
    {
        // get our ECC data in from the relevant file
        $format_info = $this->readECCData($matrix_remain_bit, $max_codewords);
      
        $codewordsCounter = $this->divideBy8Bit();
        $codewordsCounter = $this->setPaddingCharacter($codewordsCounter);

        // Do the actual RS-ECC magic
        $this->doRSCalculations();
      
        return $format_info; // used in main makeCode()
    }

    /**
     * Read in correct baseline data from ECC/RS files
     * 
     * @param array   $matrix_remain_bit array of remaining bits for data matrix
     * @param integer $max_codewords     number of maximum codewords
     * 
     * @return array
     */
    protected function readECCData($matrix_remain_bit, $max_codewords)
    {
        $this->byte_num = $matrix_remain_bit[$this->version]
            + ($max_codewords << 3);
        $filename = $this->path
            . "/qrv"
            . $this->version
            . "_"
            . $this->ec
            . ".dat";
        if (!file_exists($filename)) {
            throw new Image_QRCode_Exception("Can't open ECC data file");
        }
        $fp1 = fopen($filename, "rb");
        
        $matx = fread($fp1, $this->byte_num);
        $maty = fread($fp1, $this->byte_num);
        $masks = fread($fp1, $this->byte_num);
        $fi_x = fread($fp1, 15);
        $fi_y = fread($fp1, 15);
        $this->rs_ecc_codewords = ord(fread($fp1, 1));
        $rso = fread($fp1, 128);
        fclose($fp1);

        $this->matrix_x_array = unpack("C*", $matx);
        $this->matrix_y_array = unpack("C*", $maty);
        $this->mask_array = unpack("C*", $masks);

        $this->rs_block_order = unpack("C*", $rso);

        $format_information_x2 = unpack("C*", $fi_x);
        $format_information_y2 = unpack("C*", $fi_y);

        $format_information_x1 = array(0,1,2,3,4,5,7,8,8,8,8,8,8,8,8);
        $format_information_y1 = array(8,8,8,8,8,8,8,8,7,5,4,3,2,1,0);

        $format_info = array(
          "x1" => $format_information_x1,
          "x2" => $format_information_x2,
          "y1" => $format_information_y1,
          "y2" => $format_information_y2
        );

        $this->max_data_codewords = ($this->max_data_bits >> 3);

        $filename = $this->path . "/rsc" . $this->rs_ecc_codewords . ".dat";
        if (!file_exists($filename)) {
            throw new Image_QRCode_Exception("Can't open rsc data file");
        }
        $fp0 = fopen($filename, "rb");
        
        $i = 0;
        while ($i < 256) {
            $this->rs_cal_table_array[$i] = fread($fp0, $this->rs_ecc_codewords);
            $i++;
        }
        fclose($fp0);

        /* Set terminator for data */

        if ($this->total_data_bits <= $this->max_data_bits-4) {
            $this->data_value[$this->data_counter] = 0;
            $this->data_bits[$this->data_counter] = 4;
        } else {
            if ($this->total_data_bits < $this->max_data_bits) {
                $this->data_value[$this->data_counter] = 0;
                $this->data_bits[$this->data_counter]
                    = $this->max_data_bits-$this->total_data_bits;
            } else {
                if ($this->total_data_bits > $this->max_data_bits) {
                    throw new Image_QRCode_Exception("Overflow exception");
                }
            }
        }

        return $format_info;
    }

    /**
     * Gets data into 8-bit format
     * 
     * @return integer
     */
    protected function divideBy8Bit()
    {
        $i = 0;
        $codewords_counter = 0;
        $this->codewords[0] = 0;
        $remaining_bits = 8;

        while ($i <= $this->data_counter) {

            $buffer = @$this->data_value[$i];
            $buffer_bits = @$this->data_bits[$i];

            $flag = 1;
            while ($flag) {

                if ($remaining_bits > $buffer_bits) {
                    $this->codewords[$codewords_counter]
                        = ((@$this->codewords[$codewords_counter] << $buffer_bits)
                          | $buffer);
                    $remaining_bits -= $buffer_bits;
                    $flag = 0;
                } else {
                    $buffer_bits -= $remaining_bits;
                    $this->codewords[$codewords_counter]
                        = (($this->codewords[$codewords_counter] << $remaining_bits)
                          | ($buffer >> $buffer_bits));

                    if ($buffer_bits == 0) {
                        $flag = 0;
                    } else {
                        $buffer = ($buffer & ((1 << $buffer_bits)-1));
                        $flag = 1;
                    }

                    $codewords_counter++;
                    if ($codewords_counter < $this->max_data_codewords-1) {
                        $this->codewords[$codewords_counter] = 0;
                    }
                    $remaining_bits = 8;
                }
            }
            $i++;
        }

        if ($remaining_bits != 8) {
            $this->codewords[$codewords_counter]
                = $this->codewords[$codewords_counter] << $remaining_bits;
        } else {
            $codewords_counter--;
        }

        return $codewords_counter;
    }

    /**
     * Sets the padding character to pad out data
     * 
     * @param integer $codewordsCounter counter for current no. of codewords
     * 
     * @return integer
     */
    protected function setPaddingCharacter($codewordsCounter)
    {
        if ($codewordsCounter < $this->max_data_codewords-1) {
            $flag = 1;
            while ($codewordsCounter < $this->max_data_codewords-1) {

                $codewordsCounter++;
                if ($flag == 1) {
                    $this->codewords[$codewordsCounter] = 236;
                } else {
                    $this->codewords[$codewordsCounter] = 17;
                }
                $flag = $flag * -1;
            }
        }
        return $codewordsCounter;
    }

    /**
     * Calculates the format information for the barcode
     * 
     * @param array   $format_info format information from data files
     * @param integer $mask_number mask integer to use
     * 
     * @return void
     */
    protected function calculateFormatInformation($format_info, $mask_number)
    {
        $format_value = (($this->ec << 3) | $mask_number);
        $i = 0;
        while ($i < 15) {
            $content = substr($this->format_array[$format_value], $i, 1);

            $x = $format_info["x1"][$i];
            $y = $format_info["y1"][$i];
            $this->matrix_content[$x][$y] = $content * 255;

            $x = $format_info["x2"][$i+1];
            $y = $format_info["y2"][$i+1];
            $this->matrix_content[$x][$y] = $content * 255;

            $i++;
        }
    }

    /**
     * Perform the actual RS calculations on the data
     * 
     * @return void
     */
    protected function doRSCalculations()
    {
        // Preparation
        $i = 0;
        $j = 0;
        $rs_block_number = 0;
        $rs_temp[0] = "";

        while ($i < $this->max_data_codewords) {
            $rs_temp[$rs_block_number] .= chr($this->codewords[$i]);
            $j++;

            $v = $this->rs_block_order[$rs_block_number+1]
                - $this->rs_ecc_codewords;
            if ($j >= $v) {
                $j = 0;
                $rs_block_number++;
                $rs_temp[$rs_block_number] = "";
            }
            $i++;
        }

        // RS-ECC main calculation
        $rs_block_number = 0;
        $this->rs_block_order_num = count($this->rs_block_order);

        while ($rs_block_number < $this->rs_block_order_num) {
            $rs_codewords = $this->rs_block_order[$rs_block_number+1];
            $rs_data_codewords = $rs_codewords-$this->rs_ecc_codewords;

            $rstemp = $rs_temp[$rs_block_number]
                . str_repeat(chr(0), $this->rs_ecc_codewords);
            $padding_data = str_repeat(chr(0), $rs_data_codewords);

            $j = $rs_data_codewords;
            while ($j > 0) {
                $first = ord(substr($rstemp, 0, 1));

                if ($first) {
                    $left_chr = substr($rstemp, 1);
                    $cal = $this->rs_cal_table_array[$first] . $padding_data;
                    $rstemp = $left_chr ^ $cal;
                } else {
                    $rstemp=substr($rstemp, 1);
                }

                $j--;
            }

            $this->codewords = array_merge(
                $this->codewords,
                unpack("C*", $rstemp)
            );

            $rs_block_number++;
        }
    }

    /**
     * Creates the final GD output image block
     * ready for sending wherever the user desires
     *
     * @param integer $mib destination image width/height
     *
     * @return void
     */
    protected function createOutputImage($mib)
    {
        imagecopyresized(
            $this->output_image,
            $this->base_image,
            0,
            0,
            0,
            0,
            $this->image_size,
            $this->image_size,
            $mib,
            $mib
        );
    }
    
    /**
     * Adds m/n and parity data for structure append (if supplied)
     * 
     * @return void
     */
    protected function applyStructureAppend()
    {
        if (isset($this->structureappend_n) && $this->structureappend_n > 1
            && $this->structureappend_n <= 16
            && $this->structureappend_m > 0
            && $this->structureappend_m <= 16
        ) {

            $this->data_value[0] = 3;
            $this->data_bits[0] = 4;

            $this->data_value[1] = $this->structureappend_m - 1;
            $this->data_bits[1] = 4;

            $this->data_value[2] = $this->structureappend_n - 1;
            $this->data_bits[2] = 4;


            $originaldata_length = strlen($this->structureappend_originaldata);
            if ($originaldata_length > 1) {
                $this->structureappend_parity = 0;
                $i = 0;
                while ($i < $originaldata_length) {
                    $ord = ord(
                        substr(
                            $this->structureappend_originaldata,
                            $i,
                            1
                        )
                    );
                    $this->structureappend_parity
                        = $this->structureappend_parity ^ $ord;
                    $i++;
                }
            }

            $this->data_value[3] = $this->structureappend_parity;
            $this->data_bits[3] = 8;

            $this->data_counter = 4;
        }
    }
    
    /**
     * Attaches the calculated codeword data to the data matrix
     * 
     * @param integer $max_codewords     Maximum number of codewords for this code
     * @param integer $matrix_remain_bit Dependant on the version of the code
     * 
     * @return void
     */
    protected function attachCodewordData($max_codewords, $matrix_remain_bit)
    {
        $i = 0;
        while ($i < $max_codewords) {
            $codeword_i=$this->codewords[$i];
            $j = 8;
            while ($j >= 1) {
                $codeword_bits_number = ($i << 3) +    $j;

                $x = $this->matrix_x_array[$codeword_bits_number];
                $y = $this->matrix_y_array[$codeword_bits_number];

                $this->matrix_content[$x][$y]
                    = ((255*($codeword_i & 1))
                      ^ $this->mask_array[$codeword_bits_number]);
                    
                $codeword_i= $codeword_i >> 1;
                $j--;
            }
            $i++;
        }

        $matrix_remain = $matrix_remain_bit[$this->version];
        while ($matrix_remain) {
            $remain_bit_temp = $matrix_remain + ( $max_codewords << 3);

            $x = $this->matrix_x_array[$remain_bit_temp];
            $y = $this->matrix_y_array[$remain_bit_temp];

            $this->matrix_content[$x][$y]
                = (255 ^ $this->mask_array[$remain_bit_temp]);
            $matrix_remain--;
        }
    }
    
    /**
     * Selects the mask to use
     * 
     * @param integer $max_modules_1side Max number of modules (single side) 
     * 
     * @return integer
     */
    protected function maskSelection($max_modules_1side)
    {
        $min_demerit_score = 0;
        $hor_master = "";
        $ver_master = "";
        $k = 0;
        while ($k < $max_modules_1side) {
            $l = 0;
            while ($l < $max_modules_1side) {
                $hor_master = $hor_master . chr($this->matrix_content[$l][$k]);
                $ver_master = $ver_master . chr($this->matrix_content[$k][$l]);
                $l++;
            }
            $k++;
        }
        $i = 0;
        $all_matrix = $max_modules_1side * $max_modules_1side;
        while ($i < 8) {
            $demerit_n1 = 0;
            $ptn_temp = array();
            $bit = 1 << $i;
            $bit_r = (~$bit) & 255;
            $bit_mask = str_repeat(chr($bit), $all_matrix);
            $hor = $hor_master & $bit_mask;
            $ver = $ver_master & $bit_mask;

            $ver_shift1 = $ver . str_repeat(chr(170), $max_modules_1side);
            $ver_shift2 = str_repeat(chr(170), $max_modules_1side) . $ver;
            $ver_shift1_0 = $ver . str_repeat(chr(0), $max_modules_1side);
            $ver_shift2_0 = str_repeat(chr(0), $max_modules_1side) . $ver;
            $ver_or = chunk_split(
                ~($ver_shift1 | $ver_shift2),
                $max_modules_1side, chr(170)
            );
            $ver_and = chunk_split(
                ~($ver_shift1_0 & $ver_shift2_0),
                $max_modules_1side, chr(170)
            );

            $hor = chunk_split(~$hor, $max_modules_1side, chr(170));
            $ver = chunk_split(~$ver, $max_modules_1side, chr(170));
            $hor = $hor . chr(170) . $ver;

            $n1_search = "/"
                . str_repeat(chr(255), 5)
                . "+|"
                . str_repeat(chr($bit_r), 5)
                . "+/";
            $n3_search = chr($bit_r)
                . chr(255)
                . chr($bit_r)
                . chr($bit_r)
                . chr($bit_r)
                . chr(255)
                . chr($bit_r);

            $demerit_n3 = substr_count($hor, $n3_search) * 40;

            $sc = substr_count($ver, chr($bit_r));
            $demerit_n4 = floor(abs(((100 * ($sc / ($this->byte_num))) - 50) / 5));
            $demerit_n4 *= 10;

            $n2_search1 = "/" . chr($bit_r) . chr($bit_r) . "+/";
            $n2_search2 = "/" . chr(255) . chr(255) . "+/";
            $demerit_n2 = 0;

            preg_match_all($n2_search1, $ver_and, $ptn_temp);
            foreach ($ptn_temp[0] as $str_temp) {
                $demerit_n2 += (strlen($str_temp) - 1);
            }

            $ptn_temp = array();
            preg_match_all($n2_search2, $ver_or, $ptn_temp);
            foreach ($ptn_temp[0] as $str_temp) {
                $demerit_n2 += (strlen($str_temp) - 1);
            }
            $demerit_n2*=3;

            $ptn_temp=array();
            preg_match_all($n1_search, $hor, $ptn_temp);
            foreach ($ptn_temp[0] as $str_temp) {
                $demerit_n1 += (strlen($str_temp) - 2);
            }

            $demerit_score = $demerit_n1 + $demerit_n2;
            $demerit_score += $demerit_n3 + $demerit_n4;

            if ($demerit_score <= $min_demerit_score || $i == 0) {
                $mask_number = $i;
                $min_demerit_score = $demerit_score;
            }

            $i++;
        }
        
        return $mask_number;
    }
    
    /**
     * Clears the output matrix array, ready for new data
     * 
     * @param integer $max_modules_1side Max number of modules (single side)
     * 
     * @return void
     */
    protected function emptyMatrix($max_modules_1side)
    {
        $i = 0;
        while ($i < $max_modules_1side) {
            $j = 0;
            while ($j < $max_modules_1side) {
                $this->matrix_content[$j][$i] = 0;
                $j++;
            }
            $i++;
        }
    }
    
    /**
     * Creates the base square image, based on the module size
     * 
     * @param integer $max_modules_1side Max number of modules (single side)
     * 
     * @return integer
     */
    protected function createBaseImage($max_modules_1side)
    {
        $mib = $max_modules_1side+8;
        $this->image_size = $mib * $this->module_size;
        if ($this->image_size > 1480) {
            throw new Image_QRCode_Exception("Image size is too large");
        }

        // image is square
        $this->output_image = imagecreate(
            $this->image_size,
            $this->image_size
        );
        
        // load our base image in
        $image_path = $this->image_path . "/qrv" . $this->version . ".png";
        if (!file_exists($image_path)) {
            throw new Image_QRCode_Exception("Base image not found");
        }
        $this->base_image = imagecreatefrompng($image_path);
        if (!$this->base_image) {
            throw new Image_QRCode_Exception("Couldn't load base image");
        }
        
        return $mib;
    }
    
    /**
     * Adds the calculated matrix data to the base image.
     * 
     * @param integer $max_modules_1side Max number of modules (single side)
     * @param integer $mask_content      Mask based on original data
     * 
     * @return void
     */
    protected function addMatrixToImage($max_modules_1side, $mask_content)
    {
        // Create colours that we need to use
        // NB if you want your barcode to be in a different colour,
        // simply changing these colours will not work, as the base image
        // is in black and white to start with.
        $col[1] = imagecolorallocate($this->base_image, 0, 0, 0);
        $col[0] = imagecolorallocate($this->base_image, 255, 255, 255);

        $i = 4;
        $mxe = 4 + $max_modules_1side;
        $ii = 0;
        while ($i < $mxe) {
            $j = 4;
            $jj = 0;
            while ($j < $mxe) {
                if ($this->matrix_content[$ii][$jj] & $mask_content) {
                    imagesetpixel($this->base_image, $i, $j, $col[1]);
                }
                $j++;
                $jj++;
            }
            $i++;
            $ii++;
        }
    }
}
?>
