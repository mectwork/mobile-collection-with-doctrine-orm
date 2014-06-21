<?php

namespace Cubalider\Component\Mobile\Manager;

use Cubalider\Component\Mobile\Model\Collection;
use Doctrine\ORM\EntityManager;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionManager implements CollectionManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * Constructor
     * Additionally it creates a repository using $em, for given class
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('Cubalider\Component\Mobile\Model\Collection');
    }

    /**
     * @inheritdoc
     */
    public function pick($criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @inheritdoc
     */
    public function collect()
    {
        return $this->repository->findAll();
    }

    /**
     * @inheritdoc
     */
    public function add(Collection $collection)
    {
        $this->em->persist($collection);
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function delete(Collection $collection)
    {
        $this->em->remove($collection);
        $this->em->flush();
    }
}