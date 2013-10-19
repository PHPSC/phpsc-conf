<?php
namespace PHPSC\Conference\UI\Admin\Supporters;

use Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;
use Lcobucci\DisplayObjects\Components\Datagrid\DatagridColumn;
use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\Domain\Entity\Event;

class Grid extends UIComponent
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var array
     */
    protected $supporters;

    /**
     * @param Event $event
     * @param array $supporters
     */
    public function __construct(Event $event, array $supporters)
    {
        Main::appendScript($this->getUrl('js/vendor/jquery.form.min.js'));
        Main::appendScript($this->getUrl('js/adm/supporter/window.js'));

        $this->event = $event;
        $this->supporters = $supporters;
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
        $datagrid = new Datagrid(
            'supporters',
            $this->supporters,
            array(
                new DatagridColumn('Nome', 'company.name', 'col-md-3'),
                new DatagridColumn(
                    'CNPJ',
                    'company.socialId',
                    'col-md-2',
                    function ($socialId) {
                        return sprintf(
                            '%s.%s.%s/%s-%s',
                            substr($socialId, 0, 2),
                            substr($socialId, 2, 3),
                            substr($socialId, 5, 3),
                            substr($socialId, 8, 4),
                            substr($socialId, 12)
                        );
                    }
                ),
                new DatagridColumn('Email', 'company.email', 'col-md-3'),
                new DatagridColumn('Site', 'company.website', 'col-md-2'),
                new DatagridColumn(
                    '',
                    'id',
                    'col-md-2',
                    function ($id) {
                        return '<div class="pull-right">
                                    <a href="#"
                                        id="edit-' . $id . '"
                                        class="btn btn-xs btn-info disabled"
                                        title="Editar">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a href="#"
                                        id="remove-' . $id . '"
                                        class="btn btn-xs btn-danger disabled"
                                        title="Remover">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>';
                    }
                )
            )
        );

        $datagrid->setStyleClass('table table-striped');

        return $datagrid;
    }

    /**
     * @return SupporterWindow
     */
    protected function getSupporterModal()
    {
        return new SupporterWindow();
    }
}
