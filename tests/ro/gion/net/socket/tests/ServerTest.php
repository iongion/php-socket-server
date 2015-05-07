<?php
namespace ro\gion\net\socket\tests;

use ro\gion\net\socket\Server;

class ServerTest extends \PHPUnit_Framework_TestCase {
  
  const TEST_PORT = 4444;
  const TEST_ADDRESS = '127.0.0.1';
  
  protected function setUp() {}
  protected function tearDown() {}
  
  public function testCreate() {
    $inst = new Server(self::TEST_PORT, self::TEST_ADDRESS);
    $this->assertInstanceOf('ro\\gion\\net\\socket\\Server', $inst);
  }
  
}
