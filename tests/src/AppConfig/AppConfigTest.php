<?php
/**
 * AppConfig tests
 *
 */
namespace Tests\AppConfig;

use AppConfig\AppConfig,
    AppConfig\Loaders\YamlLoader,
    \InvalidArgumentException,
    \Exception;

class AppConfigTest extends \PHPUnit_Framework_TestCase
{
  protected $configArray;

  protected function setUp()
  {
    //set up and configure stub
    $this->configArray = array(
      'groupone' => array(
        'key' => array(
          'one'   => 'one',
          'two'   => 'two',
          'three' => 'three',
        )
      ),
      'grouptwo' => array(
        'one'   => 'one',
        'two'   => 'two',
        'three' => 'three',
      ),
    );

    $this->stub = $this->getMock('YamlLoader', array('load'));

    $this->stub->expects($this->any())
         ->method('load')
         ->will($this->returnValue($this->configArray));

  }

  public function testLoad()
  {
    // bad run - invalid loader
    try {
      $fakeLoader = "fake loader";
      AppConfig::load($fakeLoader);
      $this->fail("Exception failed to raise");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Incorrect exception raised: ".$e);
    }

    // good run
    try {
      AppConfig::load($this->stub);
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Good load of AppConfig failed: ".$e);
    }

  }

  public function testGet()
  {
    AppConfig::load($this->stub);

    // good values
    $this->assertEquals('two', AppConfig::get('groupone', 'key.two'));
    $this->assertEquals('three', AppConfig::get('grouptwo', 'three'));

    // bad values
    try {
      // invalid key name
      $rv = AppConfig::get('groupone', 'invalidkey');
      var_dump($rv);
      $this->fail("Exception failed to raise");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
    try {
      // invalid group name
      $rv = AppConfig::get('invalidgroup', 'key.one');
      $this->fail(" Exception failed to raise");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
    try {
      // invalid group and invalid key
      $rv = AppConfig::get('invalidgroup', 'invalidkey');
      $this->fail("Exception failed to raise");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
  }

}
