<?php

namespace Cubalider\Component\Mobile\Manager;

use Yosmanyga\Component\Dql\Fit\Builder;
use Yosmanyga\Component\Dql\Fit\WhereCriteriaFit;
use Cubalider\Component\Mobile\Model\Collection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionManager implements CollectionManagerInterface
{
    /**
     * @var string
     */
    private $class = 'Cubalider\Component\Mobile\Model\Collection';

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var Builder;
     */
    private $builder;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em
     * @param Builder       $builder
     */
    public function __construct(EntityManagerInterface $em, Builder $builder = null)
    {
        $this->em = $em;
        $this->builder = $builder ?: new Builder($em);
    }

    /**
     * @inheritdoc
     */
    public function pick($criteria)
    {
        $qb = $this->builder->build(
            $this->class,
            new WhereCriteriaFit($criteria)
        );

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritdoc
     */
    public function collect()
    {
        $qb = $this->builder->build(
            $this->class
        );

        return $qb
            ->getQuery()
            ->getResult();
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