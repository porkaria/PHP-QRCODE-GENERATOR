<?php

class Image_QRCodeTest extends PHPUnit_Framework_TestCase
{
	  public $image_qrcode = null;
	  
	  /**
	   * setUp
	   * 
	   * @return void
	   */
	  public function setUp()
	  {
	      $this->image_qrcode = new Image_QRCode();
	  }
	  
	  /**
	   * tearDown
	   * 
	   * @return void
	   */
	  public function tearDown()
	  {
	      $this->image_qrcode = null;
	  }
	  
	  /**
	   * testSetVersionTooLow
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetVersionTooLow()
	  {
	      $arr = array(
	          "version" => -1
	      );
	      $testObj = new Image_QRCode($arr);
	  }
	  
	  /**
	   * testSetVersionTooHigh
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetVersionTooHigh()
	  {
	      $arr = array(
	          "version" => 41
	      );
	      $testObj = new Image_QRCode($arr);
	  }
	  
	  /**
	   * testSetInvalidErrorCorrection
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetInvalidErrorCorrection()
	  {
	      $arr = array(
	          "error_correct" => "A" // should be L, M, Q, H
	      );
	      $this->image_qrcode->makeCode("Hello, world", $arr);
	  }
	  
	  /**
	   * testSetInvalidImageType
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetInvalidImageType()
	  {
	      $arr = array(
	          "image_type" => "xxx"
	      );
	      $this->image_qrcode->makeCode("Hello, world", $arr);
	  }
	  
	  /**
	   * testSetModuleSizeTooLow
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetModuleSizeTooLow()
	  {
	      $arr = array(
	          "module_size" => -1
	      );
	      $this->image_qrcode->makeCode("Hello, world", $arr);
	  }
	  
	  /**
	   * testSetModuleSizeTooHigh
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetModuleSizeTooHigh()
	  {
	      $arr = array(
	          "module_size" => 999999
	      );
	      $this->image_qrcode->makeCode("Hello, world", $arr);
	  }  
	  
	  /**
	   * testSetInvalidOutputType
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetInvalidOutputType()
	  {
	      $arr = array(
	          "output_type" => "xxx"
	      );
	      $this->image_qrcode->makeCode("Hello, world", $arr);
	  }
	  
	  /**
	   * testSetInvalidImagePath()
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetInvalidImagePath()
	  {
	      $arr = array(
	          "image_path" => "."
	      );
	      $testObj = new Image_QRCode($arr);
	      $testObj->makeCode("Hello, world");
	  }
	  
	  /**
	   * testSetInvalidDataPath()
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetInvalidDataPath()
	  {
	      $arr = array(
	          "path" => "."
	      );
	      $testObj = new Image_QRCode($arr);
	      $testObj->makeCode("Hello, world");
	  }
	  
	  /**
	   * testSetEmptyData - check that we can't supply empty data
	   * 
	   * @expectedException Image_QRCode_Exception
	   * @return void
	   */
	  public function testSetEmptyData()
	  {
	      $this->image_qrcode->makeCode("");
	  }
	  
	  /**
	   * testReturnGDResource
	   * 
	   * @return void
	   */
	  public function testReturnGDResource()
	  {
	      $arr = array(
	          "output_type" => "return"
	      );
	      $gd = $this->image_qrcode->makeCode("Hello, world", $arr);
	      $this->assertType("resource", $gd);
	  }
}