<?php
namespace PHPSC\Conference\Application\Action;

class CommunityFeedbackFilter extends BasicFilter
{
    /**
     * @see \Lcobucci\ActionMapper2\Routing\Filter::process()
     */
    public function process()
    {
        $this->validateTwitterSession();
        $this->validateUserRegistration();
    }
}