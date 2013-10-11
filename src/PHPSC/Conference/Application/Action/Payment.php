<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\PaymentManagementService;

class Payment extends Controller
{
    /**
     * @Route("/notification", methods={"POST"})
     */
    public function notification()
    {
        if ($code = $this->request->request->get('notificationCode')) {
            $this->getPaymentService()->updatePaymentStatus($code);
        }
    }

    /**
     * @return PaymentManagementService
     */
    protected function getPaymentService()
    {
        return $this->get('payment.management.service');
    }
}
