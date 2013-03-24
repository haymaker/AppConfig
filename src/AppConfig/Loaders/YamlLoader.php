<?php
namespace AppConfig\Loaders;

use AppConfig\Loaders\LoaderInterface,
    \InvalidArgumentException;

/**
  * FileLoader Class for AppConfig
  *
  * Part of the loaders namespace.  This is used to load configuration
  * directives from YAML files to the AppConfig/Container object.
  *
  * @author Mike Hayman <mikehayman@gmail.com>
  * @package AppConfig\Loaders
  * @version 0.1.0
  */
class YamlLoader implements LoaderInterface {

  /** @type string a path to a yaml file */
  private $filename;
  /** @type array application config array */
  private $appConfig;

  /**
   * Class constructor
   *
   * Object instantiation taskes a filename (path + file) as the
   * argument.  If the file doesn't exist or isn't readable, an
   * exception is thrown.  otherwise, it sets the filename variable.
   *
   * @param string $filename Full path to file
   * @throws InvalidArgumentException if the file is nonexistent or unreadable
   */
  public function __construct($filename)
  {
    if(!file_exists($filename) || !is_readable($filename))
      throw new InvalidArgumentException("YAML File ".$filename." not found or unreadable.");

    $this->filename = $filename;
  }

  /**
   * Config Loader
   *
   * Performs the heavy lifting of reading the file and parsing the config into
   * an array that will be passed to the configuration container.
   *
   * @throws InvalidArgumentException if the file has a syntax or other error
   * @return array A crafted multidimensional array of configuration keys/values
   */
  public function load()
  {
    // load yaml file into array
    $yaml = @yaml_parse_file($this->filename);


    if($yaml === false)
      throw new InvalidArgumentException("YAML File ".$this->filename." cannot be read: contains invalid syntax.");

    $this->appConfig = $yaml;

    return $yaml;
  }

  /**
   * Return the configuration container
   *
   * @return array The configurtion loaded from yaml file
   */
  public function get() {
    if(!is_array($this->appConfig) || empty($this->appConfig))
      $this->load();

    return $this->appConfig;
  }

}