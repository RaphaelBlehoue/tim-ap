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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

final class UserHandlerSubscriber implements EventSubscriberInterface
{

    private const POST_USER_REGISTER_ROUTE = 'api_register';

    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;


    public function __construct(PhoneNumberUtil $phoneNumberUtil)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            //KernelEvents::VIEW => ['SignInUser', EventPriorities::PRE_WRITE],
            KernelEvents::VIEW => ['ConvertPhoneNumber', EventPriorities::PRE_VALIDATE]
        ];
    }


    public function SignInUser(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $request = $event->getRequest();
        if (!$user instanceof User || !$request->isMethod(Request::METHOD_POST)) {
            return;
        }
        if (self::POST_USER_REGISTER_ROUTE !== $request->attributes->get('_route')) {
            return;
        }
        //dump($event->getControllerResult());
        //die;
    }


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