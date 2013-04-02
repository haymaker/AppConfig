<?php
/**
 * AppConfig tests
 *
 */
namespace AppConfig;

use AppConfig\Loaders\YamlLoader,
    \InvalidArgumentException,
    \Exception;

class AppConfigTest extends \PHPUnit_Framework_TestCase
{
  protected $configArray;
  protected $obj;
  protected $loaderStub;

  protected function setUp()
  {
    $this->obj = new AppConfig();

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

    $this->loaderStub = $this->getMock('YamlLoader', array('load'));

    $this->loaderStub->expects($this->any())
         ->method('load')
         ->will($this->returnValue($this->configArray));

  }

  public function testLoad()
  {
    // bad run - invalid loader
    try {
      $fakeLoader = "fake loader";
      $this->obj->load($fakeLoader);
      $this->fail("Exception failed to raise");
    } catch (Exception $e) {
      $this->assertTrue(true);
    }

    // good run
    try {
      $this->obj->load($this->loaderStub);
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Good load of AppConfig failed: ".$e);
    }

  }

  public function testGet()
  {
    $this->obj->load($this->loaderStub);

    // good values
    $this->assertEquals('two', $this->obj->get('groupone', 'key.two'));
    $this->assertEquals('three', $this->obj->get('grouptwo', 'three'));

    // bad values
    try {
      // invalid key name
      $rv = $this->obj->get('groupone', 'invalidkey');
      $this->fail("Exception failed to raise");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
    try {
      // invalid group name
      $rv = $this->obj->get('invalidgroup', 'key.one');
      $this->fail(" Exception failed to raise");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
    try {
      // invalid group and invalid key
      $rv = $this->obj->get('invalidgroup', 'invalidkey');
      $this->fail("Exception failed to raise");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
  }

}
