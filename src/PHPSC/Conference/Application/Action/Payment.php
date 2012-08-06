<?php
namespace PHPSC\Conference\Application\Action;

use \PHPSC\PagSeguro\ValueObject\Item;
use \PHPSC\PagSeguro\ValueObject\Payment\PaymentRequest;
use \Lcobucci\ActionMapper2\Routing\Annotation\Route;
use \Lcobucci\ActionMapper2\Routing\Controller;

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
     * @return \PHPSC\Conference\Domain\Service\PaymentManagementService
     */
    protected function getPaymentService()
    {
        return $this->get('payment.management.service');
    }
}