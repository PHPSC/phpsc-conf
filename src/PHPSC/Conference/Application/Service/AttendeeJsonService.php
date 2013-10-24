<?php
namespace PHPSC\Conference\Application\Service;

use Closure;
use Exception;
use InvalidArgumentException;
use PDOException;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Service\AttendeeRegistrationService;
use PHPSC\Conference\Domain\Service\DiscountCouponValidator;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\PagSeguro\Error\PagSeguroException;

class AttendeeJsonService
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var EventManagementService
     */
    protected $eventManager;

    /**
     * @var AttendeeRegistrationService
     */
    protected $attendeeRegistrator;

    /**
     * @var DiscountCouponValidator
     */
    protected $couponValidator;

    /**
     * @param AuthenticationService $authService
     * @param EventManagementService $eventManager
     * @param AttendeeRegistrationService $attendeeRegistrator
     * @param DiscountCouponValidator $couponValidator
     */
    public function __construct(
        AuthenticationService $authService,
        EventManagementService $eventManager,
        AttendeeRegistrationService $attendeeRegistrator,
        DiscountCouponValidator $couponValidator
    ) {
        $this->authService = $authService;
        $this->eventManager = $eventManager;
        $this->attendeeRegistrator = $attendeeRegistrator;
        $this->couponValidator = $couponValidator;
    }

    /**
     * @param boolean $isStudent
     * @param string $redirectTo
     * @return string
     */
    public function create($isStudent, $discountCode, $redirectTo)
    {
        return $this->handleExceptions(
            function (
                AttendeeRegistrationService $attendeeRegistrator,
                DiscountCouponValidator $couponValidator,
                Event $event,
                User $user
            ) use (
                $isStudent,
                $discountCode,
                $redirectTo
            ) {
                return $attendeeRegistrator->create(
                    $event,
                    $user,
                    $isStudent,
                    $redirectTo,
                    !empty($discountCode) ? $couponValidator->validate($discountCode) : null
                );
            },
            array(
                $this->attendeeRegistrator,
                $this->couponValidator,
                $this->eventManager->findCurrentEvent(),
                $this->authService->getLoggedUser()
            )
        );
    }

    /**
     * @param string $redirectTo
     * @return string
     */
    public function resendPayment($redirectTo)
    {
        return $this->handleExceptions(
            function (AttendeeRegistrationService $attendeeRegistrator, Event $event, User $user) use ($redirectTo) {
                return $attendeeRegistrator->resendPayment(
                    $event,
                    $user,
                    $redirectTo
                );
            },
            array(
                $this->attendeeRegistrator,
                $this->eventManager->findCurrentEvent(),
                $this->authService->getLoggedUser()
            )
        );
    }

    /**
     * @param Closure $function
     * @param array $params
     * @return string
     */
    protected function handleExceptions(Closure $function, array $params)
    {
        try {
            return json_encode(call_user_func_array($function, $params));
        } catch (InvalidArgumentException $error) {
            return json_encode(
                array(
                    'error' => $error->getMessage()
                )
            );
        } catch (PDOException $error) {
            return json_encode(
                array(
                    'error' => 'Não foi possível salvar os dados na camada de persistência'
                )
            );
        } catch (PagSeguroException $error) {
            return json_encode(
                array(
                    'error' => 'Erro de comunicação com o pagseguro'
                )
            );
        } catch (Exception $error) {
            return json_encode(
                array(
                    'error' => 'Erro interno no processamento da requisição'
                )
            );
        }
    }
}
