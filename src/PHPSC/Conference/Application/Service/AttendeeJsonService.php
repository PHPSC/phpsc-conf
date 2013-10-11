<?php
namespace PHPSC\Conference\Application\Service;

use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Service\AttendeeManagementService;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Domain\Service\PaymentManagementService;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\PagSeguro\Error\PagSeguroException;

class AttendeeJsonService
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var AttendeeManagementService
     */
    protected $attendeeManager;

    /**
     * @var EventManagementService
     */
    protected $eventManager;

    /**
     * @var PaymentManagementService
     */
    protected $paymentManager;

    /**
     * @var TalkManagementService
     */
    private $talkService;

    /**
     * @param AuthenticationService $authService
     * @param EventManagementService $eventManager
     * @param AttendeeManagementService $talkManager
     * @param PaymentManagementService $paymentManager
     * @param TalkManagementService $talkService
     */
    public function __construct(
        AuthenticationService $authService,
        EventManagementService $eventManager,
        AttendeeManagementService $attendeeManager,
        PaymentManagementService $paymentManager,
        TalkManagementService $talkService
    ) {
        $this->authService = $authService;
        $this->eventManager = $eventManager;
        $this->attendeeManager = $attendeeManager;
        $this->paymentManager = $paymentManager;
        $this->talkService = $talkService;
    }

    /**
     * @param boolean $isStudent
     * @param string $redirectTo
     * @return string
     */
    public function create($isStudent, $redirectTo)
    {
        $event = $this->eventManager->findCurrentEvent();
        $user = $this->authService->getLoggedUser();

        try {
            $attendee = $this->attendeeManager->create(
                $event,
                $user,
                $isStudent
            );

            if ($attendee->isWaitingForPayment()) {
                return $this->createPayment(
                    $attendee,
                    $event->getRegistrationCost($user, $this->talkService),
                    $redirectTo
                );
            }

            return json_encode(
                array(
                    'data' => array(
                        'id' => $attendee->getId(),
                        'redirectTo' => $redirectTo
                    )
                )
            );
        } catch (\InvalidArgumentException $error) {
            return json_encode(
                array(
                    'error' => $error->getMessage()
                )
            );
        } catch (\PDOException $error) {
            return json_encode(
                array(
                    'error' => 'Não foi possível salvar os dados na camada de persistência'
                )
            );
        } catch (\Exception $error) {
            return json_encode(
                array(
                    'error' => 'Erro interno no processamento da requisição'
                )
            );
        }
    }

    /**
     * @param Attendee $attendee
     * @param float $cost
     * @param string $redirectTo
     * @return string
     */
    protected function createPayment(Attendee $attendee, $cost, $redirectTo)
    {
        try {
            $payment = $this->paymentManager->create(
                $cost,
                $this->getItemDescription($attendee)
            );

            $this->attendeeManager->appendPayment($attendee, $payment);

            $paymentResponse = $this->paymentManager->requestPayment(
                $payment,
                $attendee->getUser()->getEmail(),
                $redirectTo
            );

            return json_encode(
                array(
                    'data' => array(
                        'id' => $attendee->getId(),
                        'redirectTo' => $paymentResponse->getRedirectionUrl()
                    )
                )
            );
        } catch (\InvalidArgumentException $error) {
            return json_encode(
                array(
                    'error' => $error->getMessage()
                )
            );
        } catch (PagSeguroException $error) {
            return json_encode(
                array(
                    'error' => 'Erro de comunicação com o pagseguro'
                )
            );
        }
    }

    /**
     * @param string $redirectTo
     * @return string
     */
    public function resendPayment($redirectTo)
    {
        $event = $this->eventManager->findCurrentEvent();
        $user = $this->authService->getLoggedUser();

        $attendee = $this->attendeeManager->findActiveRegistration(
            $event,
            $user
        );

        if ($attendee === null) {
            return json_encode(
                array('error' => 'Você não possui inscrição ativa neste evento!')
            );
        }

        if (!$attendee->isWaitingForPayment()) {
            return json_encode(
                array('error' => 'Sua inscrição não necessita de pagamento!')
            );
        }

        return $this->createPayment(
            $attendee,
            $event->getRegistrationCost($user, $this->talkService),
            $redirectTo
        );
    }

    /**
     * @param Attendee $attendee
     * @return string
     */
    protected function getItemDescription(Attendee $attendee)
    {
        $description = $this->talkService->eventHasAnyApprovedTalk($attendee->getEvent())
                       ? 'Inscrição Regular - '
                       : 'Inscrição Antecipada - ';

        $description .= $attendee->getEvent()->getName();

        return $description;
    }
}
