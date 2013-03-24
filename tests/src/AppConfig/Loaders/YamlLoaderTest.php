<?php
namespace Tests\AppConfig;

use AppConfig\Loaders\YamlLoader,
    \InvalidArgumentException,
    \Exception;

class YamlLoaderTest extends \PHPUnit_Framework_TestCase
{

  protected function setUp()
  {
    $this->goodFile = __DIR__.'/../../../fixtures/config-good.yaml';
    $this->badFile = __DIR__.'/../../../fixtures/config-bad.yaml';
    $this->noFile = __DIR__.'/thisfileshouldnotexist.yaml';
  }

  public function testConstructor()
  {
    // nonexistent file
    try {
      $a = new YamlLoader($this->noFile);
      $this->fail("Exception was not raised");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Incorrect exception was raised");
    }

    // good file
    try {
      $b = new YamlLoader($this->goodFile);
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Exception was raised on good Instantiation");
    }
  }

  public function testLoad()
  {


    // good YAML Load
    try {
      $a = new YamlLoader($this->goodFile);
      $a->load();
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Exception was raised on a good YAML load");
    }


    // yaml parsing error
    try {
      $b = new YamlLoader($this->badFile);
      $b->load();
      $this->fail("Exception was not raised on a YAML parsing error");
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    } catch (Exception $e) {
      $this->fail("Incorrect exception was raised");
    }
  }

}