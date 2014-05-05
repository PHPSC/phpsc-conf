<?php
namespace PHPSC\Conference\UI\Pages\Registration;

use PHPSC\Conference\Infra\UI\Component;

class Index extends Component
{
    /**
     * @return StudentRules
     */
    public function getStudentRules()
    {
        return new StudentRules();
    }
}
