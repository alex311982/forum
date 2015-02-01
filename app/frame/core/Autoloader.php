<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/1/15
 * Time: 6:35 PM
 */

class Autoloader {

    protected static $directories = array();

    public static function registerDirectory(array $directories)
    {
        self::$directories = $directories;
    }

    public static function loadClass($className) {
        foreach(self::$directories as $item)
        {
            if(file_exists($item."libraries/$className.php"))
            {
                require_once($item."libraries/$className.php");
            }elseif(file_exists($item."$className.php"))
            {
                require_once($item."$className.php");
            }elseif(file_exists($item."modules/$className.php"))
            {
                require_once($item."modules/$className.php");
            }elseif(file_exists($item."components/$className.php"))
            {
                require_once($item."components/$className.php");
            }
        }
    }
}
spl_autoload_register(array('AutoLoader', 'loadClass'));