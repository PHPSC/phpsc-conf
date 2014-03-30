<?php
namespace PHPSC\Conference\Infra\UI;

use Lcobucci\DisplayObjects\Core\UIComponent;

/**
 * @property-read PHPSC\Conference\Domain\Entity\Event $event The current event
 * @property-read PHPSC\Conference\Domain\Entity\User $user The logged user
 */
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
        Component::$sharedData = $sharedData;
    }

    public static function get($name)
    {
        if (Component::$sharedData !== null && isset(Component::$sharedData[$name])) {
            return Component::$sharedData[$name];
        }
    }

    /**
     * @param string $name
     * @return multitype:
     */
    public function __get($name)
    {
        return Component::get($name);
    }
}
