<?php
namespace PHPSC\Conference\UI\Admin\Credentialing;

use Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;
use Lcobucci\DisplayObjects\Components\Datagrid\DatagridColumn;
use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Attendee;

class Grid extends UIComponent
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var array
     */
    protected $attendees;

    /**
     * @param Event $event
     * @param array $attendees
     */
    public function __construct(Event $event, array $attendees)
    {
        Main::appendScript($this->getUrl('js/vendor/jquery.form.min.js'));
        Main::appendScript($this->getUrl('js/adm/credentialing/window.js'));

        $this->event = $event;
        $this->attendees = $attendees;
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
            'attendees',
            $this->attendees,
            array(
                new DatagridColumn('Nome', 'user.name', 'col-md-3'),
                new DatagridColumn('Email', 'user.email', 'col-md-3'),
                new DatagridColumn('Status', 'getStatusDescription()', 'col-md-2'),
                new DatagridColumn(
                    '',
                    'id',
                    'col-md-2',
                    function ($id, Attendee $attendee) {

                        if ($attendee->isPaymentNotVerified())
                        {
                            return '';

//                             return '<div class="pull-right">
//                                     <a href="#"
//                                         id="edit-' . $id . '"
//                                         class="btn btn-xs btn-info disabled"
//                                         title="Editar">
//                                         <span class="glyphicon glyphicon-pencil"></span>
//                                     </a>
//                                     <a href="#"
//                                         id="removex-' . $id . '"
//                                         class="btn btn-xs btn-danger disabled"
//                                         title="Removersssss">
//                                         <span class="glyphicon glyphicon-trash"></span>
//                                     </a>
//                                     <a href="#"
//                                         id="remove-' . $id . '"
//                                         class="btn btn-xs btn-danger disabled"
//                                         title="Remover">
//                                         <span class="glyphicon glyphicon-trash"></span>
//                                     </a>
//                                 </div>';
                        }

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
     * @return Window
     */
    protected function getModal()
    {
        return new Window();
    }
}