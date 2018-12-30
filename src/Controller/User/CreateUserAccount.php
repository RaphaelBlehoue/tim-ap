<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 17/12/2018
 * Time: 21:51
 */

namespace App\Controller\User;


use App\Entity\User;

class CreateUserAccount
{
    public function __invoke( User $data): User
    {
         return $data;
    }
}