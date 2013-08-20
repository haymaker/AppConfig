<?php
namespace AppConfig;

use \InvalidArgumentException;

/**
 * Configuration Container
 *
 * This class is a container class for configuration objects that are loaded
 * by the config loader.  Configurations are name-value pairs in key =>
 * value arrays.
 *
 * Structure:
 * array $config = array(group1 => array(key=>value), group2 => array(key=>value))
 *
 * @author Mike Hayman <mikehayman@gmail.com>
 * @package AppConfig
 * @version 0.1.0
 */
class ConfigContainer
{
  /** @type array Configuration Container Array */
  private $config = array();

  /**
   * Constructor
   * @param array $config Optional configuration array
   */
  public function __construct(array $config = array()) {
    if(!empty($config))
      $this->load($config);
  }

  /**
   * Loading Method
   *
   * Method that loads the configuration into the container object.
   */
  public function load(array $config)
  {
    $this->config = $config;
  }

  /**
   * Dumping method
   *
   * Dumps the entire config object.
   */
  public function dump()
  {
    return $this->config;
  }

  /**
   * groupExists Method
   *
   * Determine if config group exists.  The group names should be all
   * lowercase in the configs, but we will not assume that, and instead
   * force it.  Even still, we'll use strcmp() instead of strcasecmp()
   * so we catch any issues.
   *
   * @param string $group Group name
   * @return boolean true if exists, false otherwise
   */
  public function groupExists($group)
  {
    $groupFound = false;

    // sanitize input.
    $group = (string) $group;

    // we don't trust in_array around here.
    if(array_key_exists($group, $this->config))
      return true;

    return false;
  }

  /**
   * keyExists Method
   *
   * Determine if key exists in group The group and key names should be all
   * lowercase in the configs, but we will not assume that, and instead
   * force it.  Even still, we'll use strcmp() instead of strcasecmp()
   * so we catch any issues.
   *
   * @param string $group Group name
   * @param string $key Key name
   * @return boolean true if exists, false otherwise
   */
  public function keyExists($group, $key)
  {
    $keyFound = false;

    // sanitize input
    $group = (string) $group;
    $key = (string) $key;

    // does the group exist?
    if(!$this->groupExists($group))
      return false;

    if(!is_null($this->findVal($group, $key)))
      return true;

    // default response
    return false;
  }

  /**
   * exists Method
   *
   * This is just an alias to the keyExists method.
   *
   * @see keyExists()
   */
  public function exists($group, $key)
  {
    return $this->keyExists($group, $key);
  }


  /**
   * Get config values.
   *
   * @param  string $group Group name
   * @param  string $key   Key name
   * @return mixed         Key value
   * @return null          null is returned if key doesn't exist
   */
  public function get($group, $key)
  {
    // cast values to string.
    $group = (string) $group;
    $key = (string) $key;
    $resp = null;

    if($this->keyExists($group, $key))
      $resp = $this->findVal($group, $key);
    else
      return null;

    return $resp;
  }

  /**
   * Find the value in the config array and return it; otherwise
   * return null.
   * @param  string $group Group Name
   * @param  string $key   Key Name
   * @return mixed         Value of config key (string, int, array, etc)
   * @return null          Null returned if nothing exists
   */
  private function findVal($group, $key) {
    // does the key contain periods? if so, explode it.
    if(strpos($key, '.') !== false) {
      $key = explode('.', $key);
    }

    // if the key is a dot-delimited key, find the value.
    if(is_array($key)) {
      $config = $this->config[$group];

      for($i = 0; $i < count($key); $i++) {
        $k = $key[$i];

        if(!array_key_exists($k, $config))
          return null;

        $config = &$config[$k];
      }

      // $config should contain the value.
      return $config;
    }

    // if it's not an array, it's just a simple lookup.
    if(!array_key_exists($key, $this->config[$group]))
      return null;
    else
      return $this->config[$group][$key];

    // catch-all, we shouldn't get here!
    return null;
  }

}
