<?php

namespace Cubalider\Test\Component\Mobile\Manager;

use Cubalider\Component\Mobile\Manager\CollectionMobileManager;
use Cubalider\Component\Mobile\Model\Collection;
use Cubalider\Component\Mobile\Model\CollectionMobile;
use Cubalider\Component\Mobile\Model\Mobile;
use Yosmanyga\Component\Dql\Fit\Builder;
use Yosmanyga\Component\Dql\Fit\WhereCriteriaFit;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionMobileManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::__construct
     */
    public function testConstructor()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->getMock();
        /** @var \Yosmanyga\Component\Dql\Fit\Builder $builder */
        $builder = $this->getMockBuilder('Yosmanyga\Component\Dql\Fit\Builder')
            ->setConstructorArgs(array($em))
            ->getMock();
        $manager = new CollectionMobileManager($em, $builder);

        $this->assertAttributeEquals($em, 'em', $manager);
        $this->assertAttributeEquals($builder, 'builder', $manager);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::__construct
     */
    public function testConstructorWithDefaultParameters()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->getMock();
        /** @var \Doctrine\ORM\EntityManager $em */
        $manager = new CollectionMobileManager($em);

        $this->assertAttributeEquals(new Builder($em), 'builder', $manager);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::collect
     */
    public function testCollect()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $builder = $this->getMockBuilder('Yosmanyga\Component\Dql\Fit\Builder')
            ->disableOriginalConstructor()
            ->getMock();
        $collection = new Collection();
        $mobile = new Mobile();
        $collectionMobile = new CollectionMobile();
        $collectionMobile->setMobile($mobile);
        $collectionMobile->setCollection($collection);
        $qb = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('getResult'))
            ->getMockForAbstractClass();
        /** @var \Doctrine\ORM\EntityManager $em */
        /** @var \Yosmanyga\Component\Dql\Fit\Builder $builder */
        $manager = new CollectionMobileManager($em, $builder);

        /** @var \PHPUnit_Framework_MockObject_MockObject $builder */
        $builder
            ->expects($this->once())
            ->method('build')
            ->with(
                'Cubalider\Component\Mobile\Model\CollectionMobile',
                new WhereCriteriaFit(array(
                    'collection' => $collection->getId()
                ))
            )
            ->will($this->returnValue($qb));
        $qb
            ->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($query));
        $query
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(array($collectionMobile)));

        $this->assertEquals(array($mobile), $manager->collect($collection));
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::add
     */
    public function testAdd()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $mobile = new Mobile();
        $collection = new Collection();
        $collectionMobile = new CollectionMobile();
        $collectionMobile->setMobile($mobile);
        $collectionMobile->setCollection($collection);
        /** @var \Doctrine\ORM\EntityManager $em */
        $manager = new CollectionMobileManager($em);

        /** @var \PHPUnit_Framework_MockObject_MockObject $em */
        $em
            ->expects($this->once())->method('persist')
            ->with($this->equalTo($collectionMobile));
        $em
            ->expects($this->once())->method('flush');

        $manager->add($mobile, $collection);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionMobileManager::remove
     */
    public function testRemove()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $builder = $this->getMockBuilder('Yosmanyga\Component\Dql\Fit\Builder')
            ->disableOriginalConstructor()
            ->getMock();
        $collection = new Collection();
        $mobile = new Mobile();
        $collectionMobile = new CollectionMobile();
        $collectionMobile->setMobile($mobile);
        $collectionMobile->setCollection($collection);
        $qb = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('getOneOrNullResult'))
            ->getMockForAbstractClass();
        /** @var \Doctrine\ORM\EntityManager $em */
        /** @var \Yosmanyga\Component\Dql\Fit\Builder $builder */
        $manager = new CollectionMobileManager($em, $builder);

        /** @var \PHPUnit_Framework_MockObject_MockObject $builder */
        $builder
            ->expects($this->once())
            ->method('build')
            ->with(
                'Cubalider\Component\Mobile\Model\CollectionMobile',
                new WhereCriteriaFit(array(
                    'mobile' => $mobile->getNumber(),
                    'collection' => $collection->getId()
                ))
            )
            ->will($this->returnValue($qb));
        $qb
            ->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($query));
        $query
            ->expects($this->once())
            ->method('getOneOrNullResult')
            ->will($this->returnValue($collectionMobile));

        /** @var \PHPUnit_Framework_MockObject_MockObject $em */
        $em
            ->expects($this->once())->method('remove')
            ->with($this->equalTo($collectionMobile));
        $em
            ->expects($this->once())->method('flush');

        $manager->remove($mobile, $collection);
    }
}