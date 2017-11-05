<?php
spl_autoload_register(function($className)
{
  $file = new SplFileInfo(__DIR__ . strtr("/src/$className.php", '_', '/'));
  $path = $file->getRealPath();
  if(!empty($path))
  {
    include_once $path;
  }
});
