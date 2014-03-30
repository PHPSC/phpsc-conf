<?php
namespace PHPSC\Conference\Application\Filter;

use Lcobucci\ActionMapper2\Routing\Filter;
use PHPSC\Conference\Infra\UI\Component;

class UIFilter extends Filter
{
    /**
     * {@inheritdoc}
     */
    public function process()
    {
        foreach ($this->request->getAcceptableContentTypes() as $type) {
            if (strpos($type, 'image') || $type == 'application/json') {
                return ;
            }
        }

        $data = array(
        	'event' => $this->get('event.management.service')->findCurrentEvent(),
            'user' => $this->get('authentication.service')->getLoggedUser()
        );

        Component::setSharedData($data);
    }
}
