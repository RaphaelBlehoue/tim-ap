<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 20/12/2018
 * Time: 10:17
 */

namespace App\EventListener\Doctrine;


use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        if ( !$user instanceof UserInterface) {
            return;
        }
        $data['data'] = [
            'username'  => $user->getUsername(),
            'roles'     => $user->getRoles(),
            'statusKey' => 'authenticated',
            'status'    => true
        ];
        $event->setData($data);
    }
}