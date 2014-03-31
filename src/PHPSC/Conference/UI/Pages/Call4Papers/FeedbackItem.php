<?php
namespace PHPSC\Conference\UI\Pages\Call4Papers;

use Lcobucci\DisplayObjects\Core\ItemRenderer;

class FeedbackItem extends ItemRenderer
{
    /**
     * @return string
     */
    public function getLabel($index)
    {
        $labels = array(
            'label label-primary',
            'label label-success',
            'label label-warning',
            'label label-danger',
            'label label-default',
            'label label-info'
        );

        return $labels[$index % count($labels)];
    }

    public function getBadge()
    {
        $badges = array(
            'label label-info',
            'label label-success',
            'label label-warning',
            'label label-primary'
        );

        return $badges[$this->getItem()->getType()->getId() - 1];
    }
}
