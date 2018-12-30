<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 18/12/2018
 * Time: 12:36
 */
declare(strict_types=1);

namespace App\Handler;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

final class UserHandlerSubscriber implements EventSubscriberInterface
{

    //private const POST_USER_REGISTER_ROUTE = 'api_register';
    private const GET_USER_CURRENT_ROUTE  = 'api_users_get_current_user_item';

    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;


    public function __construct(PhoneNumberUtil $phoneNumberUtil, TokenStorageInterface $tokenStorage)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['ConvertPhoneNumber', EventPriorities::PRE_VALIDATE]
        ];
    }

    /**
     * Convert phone Number
     * @param GetResponseForControllerResultEvent $event
     */
    public function ConvertPhoneNumber(GetResponseForControllerResultEvent $event): void
    {
        $user = $event->getControllerResult();
        $request = $event->getRequest();
        if (!$user instanceof User || !$request->isMethod(Request::METHOD_POST)) {
            return;
        }
        if (null === $user->getTelephone()) {
            return;
        }
        $phone = $user->getTelephone();
        try {
            $phoneNumber = $this->phoneNumberUtil->parse($phone, PhoneNumberUtil::UNKNOWN_REGION);
        } catch (NumberParseException $e) {
            throw new UnexpectedValueException($e->getMessage(), $e->getCode(), $e);
        }
        $user->setTelephone($phoneNumber);
    }

}