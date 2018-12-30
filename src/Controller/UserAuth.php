<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 17/12/2018
 * Time: 21:51
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserAuth
{
    /**
     * @param User $data
     * @return User
     * @Route(
     *     name="api_register",
     *     path="/api/register",
     *     methods={"POST"},
     *     defaults={
     *          "_api_resource_class"=User::class,
     *          "_api_collection_operation_name"="api_register"
     *     },
     * )
     *
     */
    public function __invoke( User $data): User
    {
         return $data;
    }
}