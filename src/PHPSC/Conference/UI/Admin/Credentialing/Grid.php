<?php
namespace PHPSC\Conference\UI\Admin\Credentialing;

use Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;
use Lcobucci\DisplayObjects\Components\Datagrid\DatagridColumn;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;

class Grid extends Component
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
        Main::appendScript($this->getUrl('js/adm/credentialing/grid.js'));

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
                new DatagridColumn(
                    'Status',
                    'getStatusDescription()',
                    'col-md-2',
                    function ($description, Attendee $attendee) {
                        return '<span id="desc-' . $attendee->getId() . '">'
                                    . $description
                                . '</span>';
                    }
                ),
                new DatagridColumn(
                    '',
                    'id',
                    'col-md-2',
                    function ($id, Attendee $attendee) {
                        if ($attendee->hasArrived()) {
                            return '';
                        }

                        if ($attendee->isPaymentNotVerified() || $attendee->isWaitingForPayment()) {
                             return '<div class="pull-right" id="buttons-' . $id . '">
                                         <a href="#"
                                             id="approve-' . $id . '"
                                             class="btn btn-xs btn-info"
                                             title="Confirmar presença">
                                             <span class="glyphicon glyphicon-check"></span>
                                         </a>
                                         <a href="#"
                                             id="pay-' . $id . '"
                                             class="btn btn-xs btn-warning"
                                             title="Realizar pagamento">
                                             <span class="glyphicon glyphicon-shopping-cart"></span>
                                         </a>
                                     </div>';
                        }

                        return '<div class="pull-right" id="buttons-' . $id . '">
                                    <a href="#"
                                         id="approve-' . $id . '"
                                         class="btn btn-xs btn-info"
                                         title="Confirmar presença">
                                         <span class="glyphicon glyphicon-check"></span>
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
