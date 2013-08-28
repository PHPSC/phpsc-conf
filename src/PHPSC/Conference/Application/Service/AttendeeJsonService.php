<?php
namespace PHPSC\Conference\Application\Service;

use \PHPSC\Conference\Domain\Service\AttendeeManagementService;
use \PHPSC\Conference\Domain\Service\PaymentManagementService;
use \PHPSC\Conference\Domain\Service\EventManagementService;
use \PHPSC\Conference\Domain\Service\EmailManagementService;
use \PHPSC\PagSeguro\Error\PagSeguroException;
use \PHPSC\Conference\Domain\Entity\Attendee;

class AttendeeJsonService
{
    /**
     * @var \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected $authService;

    /**
     * @var \PHPSC\Conference\Domain\Service\AttendeeManagementService
     */
    protected $attendeeManager;

    /**
     * @var \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected $eventManager;

    /**
     * @var \PHPSC\Conference\Domain\Service\PaymentManagementService
     */
    protected $paymentManager;

    /**
     * @var \PHPSC\Conference\Domain\Service\EmailManagementService
     */
    protected $emailManager;

    /**
     * @param \PHPSC\Conference\Application\Service\AuthenticationService $authService
     * @param \PHPSC\Conference\Domain\Service\EventManagementService $eventManager
     * @param \PHPSC\Conference\Domain\Service\AttendeeManagementService $talkManager
     * @param \PHPSC\Conference\Domain\Service\PaymentManagementService $paymentManager
     * @param \PHPSC\Conference\Domain\Service\EmailManagementService $emailManager
     */
    public function __construct(
        AuthenticationService $authService,
        EventManagementService $eventManager,
        AttendeeManagementService $attendeeManager,
        PaymentManagementService $paymentManager,
        EmailManagementService $emailManager
    ) {
        $this->authService = $authService;
        $this->eventManager = $eventManager;
        $this->attendeeManager = $attendeeManager;
        $this->paymentManager = $paymentManager;
        $this->emailManager = $emailManager;
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

            $message = $this->emailManager->getMessageFromTemplate('Registration', array(
                'user_name' => $user->getName(),
                'event_name' => $event->getName(),
                'date_start' => $event->getStartDate()->format('d/m/Y'),
                'date_end' => $event->getEndDate()->format('d/m/Y'),
            ));
            $message->setTo($user->getEmail());
            $this->emailManager->send($message);

            if ($attendee->getCost() > 0) {
                return $this->createPayment($attendee, $redirectTo);
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
     * @param \PHPSC\Conference\Domain\Entity\Attendee $attendee
     * @param string $redirectTo
     * @return string
     */
    protected function createPayment(Attendee $attendee, $redirectTo)
    {
        try {
            $paymentResponse = $this->paymentManager->create(
                $attendee,
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
        $attendee = $this->attendeeManager->findActiveRegistration(
            $this->eventManager->findCurrentEvent(),
            $this->authService->getLoggedUser()
        );

        if ($attendee === null) {
            return json_encode(
                array(
                    'error' => 'Você não possui inscrição ativa neste evento!'
                )
            );
        }

        return $this->createPayment($attendee, $redirectTo);
    }
}
