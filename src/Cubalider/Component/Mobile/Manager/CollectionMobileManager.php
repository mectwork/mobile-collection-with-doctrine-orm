<?php

namespace Cubalider\Component\Mobile\Manager;

use Cubalider\Component\Mobile\Model\Collection;
use Cubalider\Component\Mobile\Model\CollectionMobile;
use Cubalider\Component\Mobile\Model\Mobile;
use Doctrine\ORM\EntityManagerInterface;
use Yosmanyga\Component\Dql\Fit\Builder;
use Yosmanyga\Component\Dql\Fit\WhereCriteriaFit;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionMobileManager implements CollectionMobileManagerInterface
{
    /**
     * @var string
     */
    private $class = 'Cubalider\Component\Mobile\Model\CollectionMobile';

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
    public function collect(Collection $collection)
    {
        $qb = $this->builder->build(
            $this->class,
            new WhereCriteriaFit(array(
                'collection' => $collection->getId()
            ))
        );
        /** @var CollectionMobile[] $collectionMobiles */
        $collectionMobiles = $qb->getQuery()->getResult();

        $mobiles = array();
        foreach ($collectionMobiles as $collectionMobile) {
            $mobiles[] = $collectionMobile->getMobile();
        }

        return $mobiles;
    }

    /**
     * @inheritdoc
     */
    public function add(Mobile $mobile, Collection $collection)
    {
        $collectionMobile = new CollectionMobile();
        $collectionMobile->setMobile($mobile);
        $collectionMobile->setCollection($collection);

        $this->em->persist($collectionMobile);

        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function remove(Mobile $mobile, Collection $collection)
    {
        $qb = $this->builder->build(
            $this->class,
            new WhereCriteriaFit(array(
                'mobile' => $mobile->getNumber(),
                'collection' => $collection->getId()
            ))
        );
        /** @var CollectionMobile[] $collectionMobiles */
        $collectionMobile = $qb->getQuery()->getOneOrNullResult();

        if ($collectionMobile) {
            $this->em->remove($collectionMobile);
            $this->em->flush();
        }
    }
}