<?php
namespace PHPSC\Conference\UI\Admin\Talks;

use Lcobucci\DisplayObjects\Core\UIComponent;

/**
 * @author Luís Otávio Cobucci Oblonczyk
 */
class EvaluatorButtons extends UIComponent
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
