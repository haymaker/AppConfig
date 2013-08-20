<?php
/**
 * ConfigContainer Tests
 *
 */
namespace Tests\AppConfig;

use AppConfig\ConfigContainer,
    \InvalidArgumentException;

class ConfigContainerTest extends \PHPUnit_Framework_TestCase
{
  protected static $confStack;

  protected static $c;

  public static function setUpBeforeClass()
  {
    // set up fixture.
    self::$confStack = array(
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
      'booleans' => array(
        'booltrue' => true,
        'boolfalse' => false,
      )
    );

    // set up config.
    self::$c = new ConfigContainer();
  }

  public function testClass()
  {
    $this->assertInstanceOf('AppConfig\ConfigContainer', self::$c);
  }

  public function testLoad()
  {
    $this->assertInstanceOf('Appconfig\ConfigContainer', new ConfigContainer(self::$confStack));
    $this->assertEmpty(self::$c->load(self::$confStack));
  }

  public function testDump()
  {
    $this->assertEquals(self::$c->dump(), self::$confStack);
  }

  public function testGroupExists()
  {
    $this->assertTrue(self::$c->groupExists('groupone'));
    $this->assertTrue(self::$c->groupExists('grouptwo'));
    $this->assertFalse(self::$c->groupExists('key.one'));
    $this->assertFalse(self::$c->groupExists('two'));
  }

  public function testKeyExists()
  {
    $this->assertTrue(self::$c->keyExists('groupone', 'key.one'));
    $this->assertTrue(self::$c->keyExists('grouptwo', 'two'));
    $this->assertFalse(self::$c->keyExists('groupone', 'three'));
    $this->assertFalse(self::$c->keyExists('groupthree', 'key.one'));
  }

  public function testExists()
  {
    $this->assertTrue(self::$c->exists('groupone', 'key.one'));
    $this->assertTrue(self::$c->exists('grouptwo', 'two'));
    $this->assertFalse(self::$c->exists('groupone', 'three'));
    $this->assertFalse(self::$c->exists('groupthree', 'key.one'));
  }

  public function testGet()
  {
    try {
      $this->assertEquals('one', self::$c->get('groupone', 'key.one'));
      $this->assertEquals('three', self::$c->get('grouptwo', 'three'));
    } catch (Exception $e) {
      $this->fail("Failure in passing section of ".__METHOD__.": ".$e);
    }

    try {
      self::$c->get('groupone', 'nokey');
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Bad Exception thrown in ".__METHOD__.": ".$e);
    }

    try {
      self::$c->get('grouponemillion', 'key.three');
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Bad Exception thrown in ".__METHOD__.": ".$e);
    }
  }

  public function testGetWithArrays()
  {
    $arr = array('one' => 'one', 'two' => 'two', 'three' => 'three');

    $this->assertCount(3, self::$c->get('groupone', 'key'));

    $this->assertEquals($arr, self::$c->get('groupone', 'key'));
  }

  public function testBooleanValues()
  {
    try {
      $bool = self::$c->get('booleans', 'booltrue');
      $this->assertTrue($bool);
    } catch (InvalidArgumentException $e) {
      $this->fail("Boolean true should be acceptable");
    }

    try {
      $bool = self::$c->get('booleans', 'boolfalse');
      $this->assertFalse($bool);
    } catch (InvalidArgumentException $e) {
      $this->fail("Boolean false should be acceptable");
    }
  }
}
