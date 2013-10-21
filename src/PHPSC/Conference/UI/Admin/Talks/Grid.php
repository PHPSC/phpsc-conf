<?php
namespace PHPSC\Conference\UI\Admin\Talks;

use Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;
use Lcobucci\DisplayObjects\Components\Datagrid\DatagridColumn;
use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\UI\Main;

class Grid extends UIComponent
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var array
     */
    protected $talks;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param Event $event
     * @param array $talks
     * @param User $user
     */
    public function __construct(Event $event, array $talks, User $user)
    {
        $this->event = $event;
        $this->talks = $talks;
        $this->user = $user;

        Main::appendScript($this->getUrl('js/vendor/jquery.form.min.js'));
        Main::appendScript($this->getUrl('js/adm/talk/window.js'));
    }

    /**
     * @return Event
     */
    protected function getEvent()
    {
        return $this->event;
    }

    /**
     * @return Datagrid
     */
    public function getDatagrid()
    {
        $user = $this->user;

        $datagrid = new Datagrid(
            'talks',
            $this->talks,
            array(
                new DatagridColumn('Título', 'title', 'col-md-4'),
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
                            case Talk::LOW_COMPLEXITY:
                                return 'Básico';
                        }
                    }
                ),
                new DatagridColumn('Data submissão', 'creationTime.format("d/m/Y H:i:s")', 'col-md-2'),
                new DatagridColumn(
                    '',
                    'id',
                    'col-md-2',
                    function ($id, Talk $talk) use ($user) {
                        if ($talk->getEvent()->isEvaluator($user)) {
                            return new EvaluatorButtons($id);
                        }

                        return new AdministratorButtons($id);
                    }
                )
            )
        );

        $datagrid->setStyleClass('table table-striped');

        return $datagrid;
    }

    /**
     * @return EvaluationWindow
     */
    protected function getEvaluationWindow()
    {
        return new EvaluationWindow();
    }
}
