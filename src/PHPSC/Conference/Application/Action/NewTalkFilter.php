<?php
namespace PHPSC\Conference\Application\Action;

class NewTalkFilter extends BasicFilter
{
    /**
     * @see \Lcobucci\ActionMapper2\Routing\Filter::process()
     */
    public function process()
    {
        $this->validateTwitterSession();
        $this->validateUserRegistration();

        $event = $this->getEventManagement()->findCurrentEvent();

        if ($this->request->getRequestedPath() == '/call4papers/submissions/new'
            && $this->request->isMethod('get')
            && !$event->isSubmissionsInterval(new \DateTime())) {
            $this->application->redirect('/call4papers');
        }
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->application->getDependencyContainer()
                                 ->get('event.management.service');
    }
}