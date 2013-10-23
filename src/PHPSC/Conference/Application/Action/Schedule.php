<?php
namespace PHPSC\Conference\Application\Action;

use DateTime;
use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Domain\Service\ScheduledItem\Locator;
use Lcobucci\ActionMapper2\Errors\BadRequestException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Schedule extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function listAll()
    {
        if (!$this->request->query->has('date')) {
            throw new BadRequestException('Você deve enviar o parâmetro "date"');
        }

        $items = $this->getScheduledItemLocator()->getByDate(
            $this->getEventManagement()->findCurrentEvent(),
            new DateTime($this->request->query->get('date'))
        );

        $data = array();

        foreach ($items as $item) {
            $data[] = $item->jsonSerialize();
        }

        $this->response->setContentType('application/json');

        return json_encode($data);
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return Locator
     */
    protected function getScheduledItemLocator()
    {
        return $this->get('scheduledItem.locator');
    }
}