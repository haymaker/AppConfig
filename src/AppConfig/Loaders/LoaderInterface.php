<?php
namespace AppConfig\Loaders;

/**
 * Loader Interface
 *
 * @author Mike Hayman <mikehayman@gmail.com>
 * @package AppConfig\Loaders
 * @version 0.1.0
 */

interface LoaderInterface
{
  public function load();
  public function get();
}