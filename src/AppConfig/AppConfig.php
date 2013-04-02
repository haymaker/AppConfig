<?php
namespace AppConfig;

use \InvalidArgumentException;

/**
 * AppConfig
 *
 * This class handles all the application configuration logic such as
 * loading, getting, and searching.
 *
 * @author Mike Hayman <mikehayman@gmail.com>
 * @package AppConfig
 * @version 0.1.0
 */
class AppConfig
{

  /** @type object ConfigContainer object */
  protected $container;

  /**
   * Constructor
   * @param object $loader Loader Object
   */
  public function __construct($loader = null) {
    $this->container = new ConfigContainer();

    if(!is_null($loader))
      $this->load($loader);
  }

  /**
   * Load Method
   *
   * Loads configuration from a loader object passed to method.
   *
   * @param object Loader Object
   */
  public function load($loader)
  {
    if(!is_object($loader))
      throw new InvalidArgumentException("Loader argument is not an object.");

    // Initialize and load the configuration
    $this->container->load($loader->load());

    // done with loader, free its memory.
    unset($loader);
  }

  /**
   * Get Method
   *
   * Returns the value of configuration key from a group.
   *
   * @param string $group Group name
   * @param string $key Key name
   * @return string Key value
   * @return boolean false if the configuration doesn't exist
   */
  public function get($group, $key)
  {
      return $this->container->get($group, $key);
  }

  /**
   * Get config container
   *
   * @return ConfigContainer config container object
   */
  public function getContainer() {
    return $this->container;
  }

}