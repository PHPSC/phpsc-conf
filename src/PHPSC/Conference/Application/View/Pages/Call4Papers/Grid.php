<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

use \Lcobucci\DisplayObjects\Components\Datagrid\DatagridColumn;
use \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;
use \Lcobucci\DisplayObjects\Core\UIComponent;
use \PHPSC\Conference\Application\View\Main;
use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\Talk;

class Grid extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    protected $event;

    /**
     * @var array
     */
    protected $talks;

    /**
     * @param array $talks
     */
    public function __construct(Event $event, array $talks)
    {
        Main::appendScript($this->getBaseUrl() . '/js/submissions.grid.js');
        Main::appendScript($this->getBaseUrl() . '/js/view-or-edit-talk.js');

        $this->event = $event;
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
     * @return boolean
     */
    public function allowSubmission()
    {
        return $this->event->isSubmissionsInterval(new \DateTime());
    }

    /**
     * @return string|\Lcobucci\DisplayObjects\Components\Datagrid\Datagrid
     */
    public function getDatagrid()
    {
        $readOnly = !$this->allowSubmission();

    	$datagrid = new Datagrid(
	        'talks',
	        $this->getTalks(),
	        array(
        		new DatagridColumn('Nome', 'title', 'span5'),
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
    		        'span2',
    		        function ($approved)
    		        {
    		            if ($approved === null) {
    		                return 'Não avaliada';
    		            }

        			    return $approved ? 'Sim' : 'Não';
        		    }
    	        ),
    	        new DatagridColumn(
	                '',
	                'id',
	                '',
	                function ($id) use ($readOnly)
	                {
	                    $title = 'Editar';
	                    $icon = 'icon-pencil';

	                    if ($readOnly) {
	                        $title = 'Ver informações';
	                        $icon = 'icon-eye-open';
	                    }

                        return '<a href="#" id="action-' . $id . '" class="btn btn-mini btn-info" title="' . $title . '">
                                    <i class="' . $icon . '"></i>
                                </a>';
	                }
                )
        	)
	    );

    	$datagrid->setStyleClass('table table-striped');

    	return $datagrid;
    }

    public function getTalkModal()
    {
        return new TalkWindow(!$this->allowSubmission());
    }
}