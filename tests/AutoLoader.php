<?php
/**
 * simple dimple autoloader.
 */
namespace Tests\AppConfig;

use \DirectoryIterator;

class AutoLoader {
 
  public static function registerDirectory($dirName) {

      $di = new DirectoryIterator($dirName);
      foreach ($di as $file) {

          if ($file->isDir() && !$file->isLink() && !$file->isDot()) {
              // recurse into directories other than a few special ones
              self::registerDirectory($file->getPathname());
          } elseif (substr($file->getFilename(), -4) === '.php') {
              // save the class name / path of a .php file found

              self::loadClass($file->getPathname());
          }
      }
  }

  public static function loadClass($classFile)
  {
      require_once($classFile);
  }

}
