<?php 
/**
 * Simple autoloader, so we don't need Composer just for this.
 */
class Autoload
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $class = strtolower($class);
            $directorios = [
                './controladores/',
                './modelos/',
                './servicios/',
                './bd/'
            ];

            foreach ($directorios as  $directorio){
                $file = $directorio . $class .'.php';                
                if (file_exists($file)) {
                    require $file;
                    return true;
                }
            }          
            return false;
        });
    }
}