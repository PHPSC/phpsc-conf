<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

use \Lcobucci\DisplayObjects\Core\ItemRenderer;

class FeedbackItem extends ItemRenderer
{
    /**
     * @return string
     */
    public function getLabel($index)
    {
        $labels = array(
            'label label-important',
            'label label-success',
            'label label-inverse',
            'label label-info',
        );

        return $labels[$index % count($labels)];
    }

    public function getBadge()
    {
        $badges = array(
            'badge badge-inverse',
            'badge badge-success',
            'badge badge-info',
            'badge badge-important'
        );

        return $badges[$this->getItem()->getType()->getId() - 1];
    }
}