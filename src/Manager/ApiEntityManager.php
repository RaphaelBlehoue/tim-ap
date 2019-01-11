<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 30/12/2018
 * Time: 15:52
 */

namespace App\Manager;


use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class ApiEntityManager
{

    /**
     * @var EntityRepository
     */
    protected $repo;

    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @var QueryBuilder
     */
    protected $qb;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->setRepository();
    }

    abstract protected function setRepository();

    /**
     * @return QueryBuilder
     */
    public function QB() {
        return $this->qb;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function get($id) {
        return $this->repo->find($id);
    }

}