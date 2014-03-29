<?php
namespace PHPSC\Conference\Infra\UI;

use Lcobucci\DisplayObjects\Core\UIComponent;

abstract class Component extends UIComponent
{
    /**
     * @var array
     */
    private static $sharedData;

    /**
     * @param array $sharedData
     */
    public static function setSharedData(array $sharedData)
    {
        static::$sharedData = $sharedData;
    }

    /**
     * @param string $name
     * @return multitype:
     */
    public function __get($name)
    {
        if (static::$sharedData !== null && isset(static::$sharedData[$name])) {
            return static::$sharedData[$name];
        }
    }
}
