<?php
namespace AppConfig;

/**
 * Application Version
 *
 * @author Mike Hayman <mikehayman@gmail.com>
 * @package AppConfig
 */
class Version
{
  const MAJOR = 0;
  const MINOR = 2;
  const PATCH = 0;

  public static function get() {
    return self::getVersion();
  }

  public static function getVersion() {
    return self::MAJOR . '.' . self::MINOR . '.' . self::PATCH;
  }

  public static function getMajor() {
    return self::MAJOR;
  }

  public static function getMinor() {
    return self::MINOR;
  }

  public static function getPatch() {
    return self::PATCH;
  }

}
