<?php
namespace PHPSC\Conference\Application\Filter;

use DateTime;
use PHPSC\Conference\Domain\Service\EventManagementService;

class NewTalkFilter extends BasicFilter
{
    /**
     * {@inheritdoc}
     */
    public function process()
    {
        $this->validateUserAuthentication();

        $event = $this->getEventManagement()->findCurrentEvent();

        if ($this->request->getRequestedPath() == '/call4papers/submissions/new'
            && $this->request->isMethod('get')
            && !$event->isSubmissionsPeriod(new DateTime())) {
            $this->application->redirect('/call4papers');
        }
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }
}
