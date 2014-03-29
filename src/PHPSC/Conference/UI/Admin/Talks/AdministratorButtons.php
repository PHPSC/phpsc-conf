<?php
namespace PHPSC\Conference\UI\Admin\Talks;

use PHPSC\Conference\Infra\UI\Component;

/**
 * @author Luís Otávio Cobucci Oblonczyk
 */
class AdministratorButtons extends Component
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    protected function getId()
    {
        return $this->id;
    }
}
