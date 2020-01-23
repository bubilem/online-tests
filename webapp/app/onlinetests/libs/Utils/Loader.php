<?php

namespace onlinetests\Utils;

/**
 * Autoincluding the other class 
 */
class Loader
{

    /**
     * Array of class types in the system
     * @var array
     */
    public static $classTypes = [
        'Controller' => 'controllers',
        'Model' => 'models',
        'View' => 'views'
    ];

    /**
     * Default class directory
     */
    const DEFAULT_CLASS_DIR = 'libs';

    /**
     * Load (require) the class by the name of class.
     * Example 1: onlinetests\A\B load app/onlinetests/libs/A/B.php class
     * Example 2: onlinetests\PageController load app/onlinetests/controllers/PageController.php class     
     * @param string $classFullName
     * @return void
     */
    public static function loadClass(string $classFullName): void
    {
        $path = self::getPath($classFullName);
        if ($path) {
            require_once $path;
            if (method_exists($classFullName, 'init')) {
                $classFullName::init();
            }
        } else {
            exit("Class $classFullName not found!");
        }
    }

    /**
     * Class path generation
     * @param string $classFullName
     * @return string
     */
    public static function getPath(string $classFullName): string
    {
        $classArray = explode('\\', $classFullName);
        if (!is_array($classArray) || count($classArray) < 2) {
            return '';
        }
        $path = APP_DIR . '/' . array_shift($classArray) . '/' . self::detectClassTypeDir($classFullName) . '/' . implode('/', $classArray) . '.php';
        return file_exists($path) ? $path : '';
    }

    /**
     * Detect class directory by class name
     * @param string $classFullName
     * @return string 
     */
    private static function detectClassTypeDir(string $classFullName): string
    {
        foreach (self::$classTypes as $classTypeName => $classTypeDirectory) {
            if (preg_match('~.*' . $classTypeName . '$~', $classFullName)) {
                return $classTypeDirectory;
            }
        }
        return self::DEFAULT_CLASS_DIR;
    }
}
