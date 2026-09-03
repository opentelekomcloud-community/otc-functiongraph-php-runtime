<?php

namespace FGDependenciesLoader;

// Class to load FunctionGraph dependencies and keep track of them
// It supports PSR-4, PSR-0, and files autoloading, but not classmap autoloading.
class FGDependenciesLoader
{
  // Array to keep track of loaded dependencies
  private $loaded = array();

  function __construct()
  {
    $this->loadDependencies();
  }

  // Get the list of loaded dependencies
  public function getLoaded()
  {
    return $this->loaded;
  }

  private function loadDependencies()
  {
    // FunctionGraph dependencies are unpacked in the vendor directory under the runtime code root
    $path = getenv('RUNTIME_CODE_ROOT') . "/vendor";
    $composerDirectory = rtrim($path, "/") . "/composer";

    // iterate through all directories recursively, ignoring the /composer folder
    $directories = new \RecursiveIteratorIterator(
      new \RecursiveCallbackFilterIterator(
        new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
        function ($dir) use ($composerDirectory) {
          return $dir->getPathname() !== $composerDirectory;
        }
      ),
      \RecursiveIteratorIterator::SELF_FIRST
    );

    // iterate through the filtered directories and load any composer.json found
    foreach ($directories as $dir) {
      if ($dir->isDir() && file_exists($dir->getPathname() . "/composer.json")) {
        $this->load($dir->getPathname());
      }
    }
  }

  // Load the composer.json file from the given directory and 
  // register autoloaders for PSR-4, PSR-0, and files.
  private function load($dir)
  {
    
    $composer = json_decode(file_get_contents($dir . "/composer.json"), 1);

    if (isset($composer["name"])) {
      array_push($this->loaded, $composer["name"]);
    } else {
      array_push($this->loaded, substr($dir, strlen(getenv('RUNTIME_CODE_ROOT')) + 1));
    }

    if (isset($composer["autoload"]["psr-4"])) {
      $this->loadPSR($dir, $composer['autoload']['psr-4'], true);
    }
    if (isset($composer["autoload"]["psr-0"])) {
      $this->loadPSR($dir, $composer['autoload']['psr-0'], false);
    }
    if (isset($composer["autoload"]["files"])) {
      $this->loadFiles($dir, $composer["autoload"]["files"]);
    }

    if (isset($composer["autoload"]["classmap"])) {
      // classmap autoloading is not supported
      throw new \Exception("Classmap autoloading is not supported in FGDependenciesLoader.");
    }
  }

  // Load the files specified in the composer.json autoload section.
  private function loadFiles($dir, $files)
  {
    foreach ($files as $file) {
      $fullpath = $dir . "/" . $file;
      if (file_exists($fullpath)) {
        include_once($fullpath);
      }
    }
  }

  // Load the PSR-4 or PSR-0 autoloaders specified in the composer.json autoload section.
  private function loadPSR($dir, $namespaces, $isPsr4)
  {

    // Foreach namespace specified in the composer, load the given classes
    foreach ($namespaces as $namespace => $classpaths) {
      if (!is_array($classpaths)) {
        $classpaths = array($classpaths);
      }
      spl_autoload_register(function ($classname) use ($namespace, $classpaths, $dir, $isPsr4) {
        // Check if the namespace matches the class we are looking for
        if (preg_match("#^" . preg_quote($namespace) . "#", $classname)) {
          // Remove the namespace from the file path since it's psr4
          if ($isPsr4) {
            $classname = str_replace($namespace, "", $classname);
          }
          $filename = preg_replace("#\\\\#", "/", $classname) . ".php";
          foreach ($classpaths as $classpath) {
            $fullpath = $dir . "/" . $classpath . "/$filename";
            if (file_exists($fullpath)) {
              include_once $fullpath;
            }
          }
        }
      });
    }
  }
}
