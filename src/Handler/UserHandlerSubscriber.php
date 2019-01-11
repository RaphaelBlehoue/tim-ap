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
use App\Entity\RequestPassword;
use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Manager\UserManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\SerializerInterface;

final class UserHandlerSubscriber implements EventSubscriberInterface
{

    //private const POST_USER_REGISTER_ROUTE = 'api_register';
    private const API_FORGET_PASSWORD_ROUTE  = 'api_forgot_password_requests_post_collection';

    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var UserManager
     */
    private $manager;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var SerializerInterface
     */
    private $serializer;


    public function __construct(PhoneNumberUtil $phoneNumberUtil, TokenStorageInterface $tokenStorage, ManagerRegistry $registry, UserManager $manager, \Swift_Mailer $mailer, SerializerInterface $serializer)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->tokenStorage = $tokenStorage;
        $this->registry = $registry;
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->serializer = $serializer;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['extendResources', EventPriorities::POST_READ],
            KernelEvents::VIEW => [
                ['ConvertPhoneNumber', EventPriorities::PRE_VALIDATE],
                ['sendCodePasswordRequest', EventPriorities::POST_VALIDATE],
                ['getUserCodePasswordRequest', EventPriorities::POST_VALIDATE],
                ['resetPasswordRequest', EventPriorities::POST_VALIDATE],
                ['sendMail', EventPriorities::POST_WRITE]
            ],
        ];
    }

    /**
     * Convert phone Number
     * @param GetResponseForControllerResultEvent $event
     * This Action convert PhoneNumber before valid to register
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

    /**
     * @param GetResponseForControllerResultEvent $event
     * @throws UserNotFoundException
     * ForgetPasswordRequest: this action check Eamil and get user by Email
     * Send Code to request password
     */
    public function sendCodePasswordRequest(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ('api_forgot_password_requests_post_collection' !== $request->attributes->get('_route')) {
            return;
        }
        if (!$request->isMethod(Request::METHOD_POST)) {
            return;
        }
        $forgotPasswordRequest = $event->getControllerResult();
        $user = $this->manager->findOneByEmail($forgotPasswordRequest->email);
        if (null === $user) {
            throw new UserNotFoundException('The user does not exist.');
        }
        if ($user) {
            $this->manager->RequestPassword($user);
        }
        $event->setResponse(new JsonResponse(['success' => true], 201));
    }


    /**
     * @param GetResponseForControllerResultEvent $event
     * @return string|JsonResponse|void
     * ForgetPasswordRequest: this action find user code in database et return user
     */
    public function getUserCodePasswordRequest(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ('api_get_user_password_requests_post_collection' !== $request->attributes->get('_route')) {
            return;
        }
        $data = $event->getControllerResult();
        // recherche dans RequestPassword le code
        $repository = $this->registry->getRepository('App\Entity\RequestPassword');
        try {
            $codeInfo = $repository->findOneByValidCodeRequest($data->code);
        } catch (NonUniqueResultException $e) {
            return $e->getMessage();
        }
        if (null === $codeInfo) {
            throw new NotFoundHttpException('le code de recupération de mot de passe est invalide');
        }
        $user = $this->manager->findOneByEmail($codeInfo->getEmail());

        if (null === $user) {
            $output = [
                'code' => 'Code utilisation introuvable',
                'error' => true
            ];
            return new JsonResponse($output, 400);
        }

        $data = $this->serializer->serialize($user, "jsonld", ["group" => "user.read"]);
        $rep = new Response($data, 200);
        $event->setResponse($rep);
    }

    public function resetPasswordRequest(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ('api_update_password_requests_put_item' !== $request->attributes->get('_route')) {
            return;
        }
        dump('reset');
        dump($request);
        die;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * This action send Email at user
     */
    public function sendMail(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $request = $event->getRequest();
        if (
            'api_forgot_password_requests_post_collection' !== $request->attributes->get('_route') ||
            Request::METHOD_POST !== $request->getMethod()
        ) {
            return;
        }
        $message = (new \Swift_Message('Request Password'))
            ->setFrom('system@example.com')
            ->setTo($user->email)
            ->setBody(sprintf('The book #%d has been added.', 123344));
        $this->mailer->send($message);
    }

    /**
     * @param GetResponseEvent $event
     */
    public function extendResources(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $class = $request->attributes->get('_api_resource_class');
        if ($class === User::class) {
            $resources = [
                '/me'
            ];
            $request->attributes->set(
                '_resources',
                $request->attributes->get('_resources', []) + (array)$resources
            );
        }
    }
}