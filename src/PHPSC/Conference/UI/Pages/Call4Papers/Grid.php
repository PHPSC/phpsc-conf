<?php
namespace PHPSC\Conference\UI\Pages\Call4Papers;

use \Lcobucci\DisplayObjects\Components\Datagrid\DatagridColumn;
use \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;
use \Lcobucci\DisplayObjects\Core\UIComponent;
use \PHPSC\Conference\UI\Main;
use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\UI\ShareButton;

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
        Main::appendScript($this->getUrl('js/view-or-edit-talk.js'));

        $this->event = $event;
        $this->talks = $talks;
    }

    public function getShareButton()
    {
        if (!$this->hasTalks()) {
            return null;
        }

        return new ShareButton(
            'Estou colaborando no ' . $this->event->getName(),
            sprintf(
                'Estou colaborando no #phpscConf com %d trabalho(s). Contribua você também!',
                $this->getTalksCount()
            ),
            'http://conf.phpsc.com.br',
            'PHP_SC'
        );
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
                new DatagridColumn('Nome', 'title', 'col-md-5'),
                new DatagridColumn('Tipo', 'type.description', 'col-md-2'),
                new DatagridColumn(
                    'Nível',
                    'complexity',
                    'col-md-2',
                    function ($complexity) {
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
                    'col-md-2',
                    function ($approved) {
                        if ($approved === null) {
                            return 'Não avaliada';
                        }

                        return $approved ? 'Sim' : 'Não';
                    }
                ),
                new DatagridColumn(
                    '',
                    'id',
                    'col-md-1',
                    function ($id) use ($readOnly) {
                        $title = 'Editar';
                        $icon = 'pencil';

                        if ($readOnly) {
                            $title = 'Ver informações';
                            $icon = 'eye-open';
                        }

                        return '<div class="pull-right">
                                    <a href="#"
                                        id="action-' . $id . '"
                                        class="btn btn-xs btn-info"
                                        title="' . $title . '">
                                        <span class="glyphicon glyphicon-' . $icon . '"></span>
                                    </a>
                                </div>';
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
