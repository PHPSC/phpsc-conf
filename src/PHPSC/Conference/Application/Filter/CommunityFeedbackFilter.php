<?php
namespace PHPSC\Conference\Application\Filter;

class CommunityFeedbackFilter extends BasicFilter
{
    /**
     * {@inheritdoc}
     */
    public function process()
    {
        $this->validateUserRegistration();
    }
}
