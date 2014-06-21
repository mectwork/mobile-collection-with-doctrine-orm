<?php

namespace Cubalider\Component\Mobile\Manager;

use Cubalider\Component\Mobile\Model\CollectionMobile;
use Cubalider\Component\Mobile\Model\Collection;
use Cubalider\Component\Mobile\Model\Mobile;
use Doctrine\ORM\EntityManager;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionMobileManager implements CollectionMobileManagerInterface
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
     * @param EntityManager $em
     */
    function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('Cubalider\Component\Mobile\Model\CollectionMobile');
    }

    /**
     * @inheritdoc
     */
    public function collect(Collection $collection)
    {
        /** @var CollectionMobile[] $collectionMobiles */
        $collectionMobiles = $this->repository->findBy(array(
            'collection' => $collection)
        );

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
        $collectionMobile = $this->repository->findOneBy(array(
            'mobile' => $mobile,
            'collection' => $collection
        ));

        $this->em->remove($collectionMobile);

        $this->em->flush();
    }
}