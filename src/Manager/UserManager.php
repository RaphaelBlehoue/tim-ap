<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 30/12/2018
 * Time: 16:00
 */

namespace App\Manager;


use App\Entity\RequestPassword;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserManager extends ApiEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);
    }

    /**
     * Init repository this entity
     */
    protected function setRepository()
    {
        $this->repo = $this->registry->getRepository('App\Entity\User');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     * This class get registry Manager this entity
     */
    protected function manager()
    {
        return $this->registry->getManagerForClass(User::class);
    }

    /**
     * @param $email
     * @return null|object
     * this class find one user line by user email
     */
    public function findOneByEmail($email) {
        return $user = $this->repo->findOneBy(['email' => $email]);
    }

    /**
     * @param $user
     * this class persist in entity RequestPassword user request password information
     */
    public function RequestPassword($user)
    {
        if (!$user instanceof User) { return; }
        $this->updateUserRequest($user);
        $requestPassword = new RequestPassword();
        $requestPassword->setPasswordOld($user->getPassword());
        $requestPassword->setEmail($user->getEmail());
        $em = $this->registry->getManagerForClass(RequestPassword::class);
        $em->persist($requestPassword);
        $em->flush();
    }

    /**
     * @return int
     * this class generate user code for 6 digits
     */
    private function generateCode() {
        return $six_digit_random_number = mt_rand(100000, 999999);
    }

    /**
     * @param $user
     * this class update User code request for password and update State HasRequestPassword
     * meaning that user has request to change password forget
     */
    private function updateUserRequest($user)
    {
        if (!$user instanceof User) { return; }
        $code = $this->generateCode();
        $user->setHasRequestPassword(true);
        $user->setCodeRequest($code);
        $this->manager()->flush();
        $this->manager()->clear();
    }
}