<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

use PHPSC\Conference\Domain\Entity\Talk;

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
        Main::appendScript($this->getBaseUrl() . '/js/submissions.grid.js');

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
     * @return number
     */
    public function getTalksCount()
    {
        return count($this->getTalks());
    }

    /**
     * @return boolean
     */
    public function hasTalks()
    {
        return $this->getTalksCount() > 0;
    }

    /**
     * @return string|\Lcobucci\DisplayObjects\Components\Datagrid\Datagrid
     */
    public function getDatagrid()
    {
    	$datagrid = new Datagrid(
	        'talks',
	        $this->getTalks(),
	        array(
        		new DatagridColumn('Nome', 'title', 'span3'),
        		new DatagridColumn('Tipo', 'type.description', 'span2'),
        		new DatagridColumn(
    		        'Nível',
    		        'complexity',
		            'span2',
    		        function ($complexity)
    		        {
    		            switch ($complexity) {
    		                case Talk::HIGH_COMPLEXITY:
    		                    return 'Avançado';
    		                case Talk::MEDIUM_COMPLEXITY:
    		                    return 'Intermediário';
    		                default:
    		                    return 'Básico';
    		            }
    		        }
		        ),
        		new DatagridColumn(
    		        'Aprovada',
    		        'approved',
    		        'span1',
    		        function ($approved)
    		        {
        			    return $approved ? 'Sim' : 'Não';
        		    }
    	        ),
    	        new DatagridColumn(
	                '',
	                'id',
	                'span1',
	                function ($id)
	                {
                        return '<a href="#" class="btn btn-mini btn-info" title="Ver informações (em breve)">
                                    <i class="icon-eye-open"></i>
                                </a>';
	                }
                )
        	)
	    );

    	$datagrid->setStyleClass('table table-striped');

    	return $datagrid;
    }
}