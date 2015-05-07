<?php
namespace ro\gion\net\socket\tests;

use ro\gion\net\socket\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {
  
  const TEST_PORT = 4444;
  const TEST_ADDRESS = '127.0.0.1';
  
  protected function setUp() {}
  protected function tearDown() {}
  
  public function testCreate() {
    $inst = new Client(self::TEST_PORT, self::TEST_ADDRESS);
    $this->assertInstanceOf('ro\\gion\\net\\socket\\Client', $inst);
  }
  
}
