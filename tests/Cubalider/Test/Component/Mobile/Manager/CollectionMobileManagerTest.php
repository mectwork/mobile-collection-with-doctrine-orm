<?php

namespace Cubalider\Test\Component\Mobile\Manager;

use Cubalider\Component\Mobile\Model\CollectionMobile;
use Cubalider\Component\Mobile\Model\Mobile;
use Cubalider\Component\Mobile\Model\Collection;
use Cubalider\Component\Mobile\Manager\CollectionMobileManager;
use Cubalider\Test\Component\Mobile\EntityManagerBuilder;
use Doctrine\ORM\EntityManager;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionMobileManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $collectionMobileClass;

    protected function setUp()
    {
        $this->collectionMobileClass = 'Cubalider\Component\Mobile\Model\CollectionMobile';

        $builder = new EntityManagerBuilder();
        $this->em = $builder->createEntityManager(
            array(
                sprintf("%s/../../../../../../src/Cubalider/Component/Mobile/Resources/config/doctrine", __DIR__),
                sprintf("%s/../../../../../../vendor/cubalider/mobile-with-doctrine-orm/src/Cubalider/Component/Mobile/Resources/config/doctrine", __DIR__)
            ),
            array(
                'Cubalider\Component\Mobile\Model\Collection',
                'Cubalider\Component\Mobile\Model\CollectionMobile',
                'Cubalider\Component\Mobile\Model\Mobile'
            )
        );
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::__construct
     */
    public function testConstructor()
    {
        $manager = new CollectionMobileManager($this->em);

        $this->assertAttributeEquals($this->em, 'em', $manager);
        $this->assertAttributeEquals($this->em->getRepository($this->collectionMobileClass), 'repository', $manager);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::collect
     */
    public function testCollect()
    {
        /* Fixtures */

        $collection = new Collection();
        $this->em->persist($collection);

        $mobile1 = new Mobile();
        $mobile1->setNumber('number1');
        $this->em->persist($mobile1);

        $mobile2 = new Mobile();
        $mobile2->setNumber('number2');
        $this->em->persist($mobile2);

        $collectionMobile1 = new CollectionMobile();
        $collectionMobile1->setCollection($collection);
        $collectionMobile1->setMobile($mobile1);
        $this->em->persist($collectionMobile1);

        $collectionMobile2 = new CollectionMobile();
        $collectionMobile2->setCollection($collection);
        $collectionMobile2->setMobile($mobile2);
        $this->em->persist($collectionMobile2);

        $this->em->flush();

        /* Test */

        $manager = new CollectionMobileManager($this->em);

        $this->assertEquals(array($mobile1, $mobile2), $manager->collect($collection));
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::add
     */
    public function testAdd()
    {
        /* Fixtures */

        $collection = new Collection();
        $this->em->persist($collection);

        $mobile = new Mobile();
        $mobile->setNumber('number');
        $this->em->persist($mobile);

        /* Test */

        $manager = new CollectionMobileManager($this->em);
        $manager->add($mobile, $collection);

        $collectionMobileRepository = $this->em->getRepository($this->collectionMobileClass);
        $collectionMobiles = $collectionMobileRepository->findAll();
        $this->assertEquals(1, count($collectionMobiles));

        /** @var CollectionMobile $collectionMobile */
        $collectionMobile = $collectionMobiles[0];
        $this->assertSame($collection, $collectionMobile->getCollection());
        $this->assertSame($mobile, $collectionMobile->getMobile());
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::remove
     */
    public function testRemove()
    {
        /* Fixtures */

        $collection = new Collection();
        $this->em->persist($collection);

        $mobile = new Mobile();
        $mobile->setNumber('number');
        $this->em->persist($mobile);

        $collectionMobile = new CollectionMobile();
        $collectionMobile->setCollection($collection);
        $collectionMobile->setMobile($mobile);
        $this->em->persist($collectionMobile);

        $this->em->flush();

        /* Test */

        $manager = new CollectionMobileManager($this->em);

        $collectionMobileRepository = $this->em->getRepository($this->collectionMobileClass);

        $manager->remove($mobile, $collection);

        $this->assertNull($collectionMobileRepository->findOneBy(array('collection' => $collection, 'mobile' => $mobile)));
    }
}