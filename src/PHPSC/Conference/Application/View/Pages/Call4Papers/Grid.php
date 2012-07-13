<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

use \PHPSC\Conference\Application\View\Main;
use \PHPSC\Conference\Domain\Entity\Event;
use \Lcobucci\DisplayObjects\Core\UIComponent;
use \Lcobucci\DisplayObjects\Components\Datagrid\DatagridColumn;
use \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;

class Grid extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    protected $event;

    /**
     * @param array $talks
     */
    public function __construct(array $talks)
    {
        $this->talks = $talks;
    }

    /**
     * @return array
     */
    public function getTalks()
    {
        return $this->talks;
    }
    
    /**
     * @return string|\Lcobucci\DisplayObjects\Components\Datagrid\Datagrid
     */
    public function getDatagrid()
    {
    	$datagrid = new Datagrid('talks', $this->getTalks(), array(
    		new DatagridColumn('#', 'id'),
    		new DatagridColumn('Nome', 'title'),
    		new DatagridColumn('Descrição Curta', 'shortDescription'),
    		new DatagridColumn('Aprovada?', 'approved', '', function($value){
    			return ($value ? 'Sim' : 'Não');
    		}),
    	));
    	$datagrid->setStyleClass('table table-striped');
    	return $datagrid;
    }
}