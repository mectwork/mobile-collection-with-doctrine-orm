<?php

namespace Cubalider\Test\Component\Mobile\Manager;

use Cubalider\Component\Mobile\Manager\CollectionManager;
use Cubalider\Component\Mobile\Model\Collection;
use Yosmanyga\Component\Dql\Fit\Builder;
use Yosmanyga\Component\Dql\Fit\WhereCriteriaFit;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::__construct
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
        $manager = new CollectionManager($em, $builder);

        $this->assertAttributeEquals($em, 'em', $manager);
        $this->assertAttributeEquals($builder, 'builder', $manager);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::__construct
     */
    public function testConstructorWithDefaultParameters()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->getMock();
        /** @var \Doctrine\ORM\EntityManager $em */
        $manager = new CollectionManager($em);

        $this->assertAttributeEquals(new Builder($em), 'builder', $manager);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::pick
     */
    public function testPick()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $builder = $this->getMockBuilder('Yosmanyga\Component\Dql\Fit\Builder')
            ->disableOriginalConstructor()
            ->getMock();
        $criteria = array('foo' => 'bar');
        $qb = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('getOneOrNullResult'))
            ->getMockForAbstractClass();
        /** @var \Doctrine\ORM\EntityManager $em */
        /** @var \Yosmanyga\Component\Dql\Fit\Builder $builder */
        $manager = new CollectionManager($em, $builder);

        /** @var \PHPUnit_Framework_MockObject_MockObject $builder */
        $builder
            ->expects($this->once())
            ->method('build')
            ->with(
                'Cubalider\Component\Mobile\Model\Collection',
                new WhereCriteriaFit($criteria)
            )
            ->will($this->returnValue($qb));
        $qb
            ->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($query));
        $query
            ->expects($this->once())
            ->method('getOneOrNullResult')
            ->will($this->returnValue('foobar'));

        $this->assertEquals('foobar', $manager->pick($criteria));
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::collect
     */
    public function testCollect()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $builder = $this->getMockBuilder('Yosmanyga\Component\Dql\Fit\Builder')
            ->disableOriginalConstructor()
            ->getMock();
        $qb = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('getResult'))
            ->getMockForAbstractClass();
        /** @var \Doctrine\ORM\EntityManager $em */
        /** @var \Yosmanyga\Component\Dql\Fit\Builder $builder */
        $manager = new CollectionManager($em, $builder);

        /** @var \PHPUnit_Framework_MockObject_MockObject $builder */
        $builder
            ->expects($this->once())
            ->method('build')
            ->with(
                'Cubalider\Component\Mobile\Model\Collection'
            )
            ->will($this->returnValue($qb));
        $qb
            ->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($query));
        $query
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue('foobar'));

        $this->assertEquals('foobar', $manager->collect());
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::add
     */
    public function testAdd()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $collection = new Collection();
        /** @var \Doctrine\ORM\EntityManager $em */
        $manager = new CollectionManager($em);

        /** @var \PHPUnit_Framework_MockObject_MockObject $em */
        $em
            ->expects($this->once())->method('persist')
            ->with($collection);
        $em
            ->expects($this->once())->method('flush');

        $manager->add($collection);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::delete
     */
    public function testDelete()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $collection = new Collection();
        /** @var \Doctrine\ORM\EntityManager $em */
        $manager = new CollectionManager($em);

        /** @var \PHPUnit_Framework_MockObject_MockObject $em */
        $em
            ->expects($this->once())->method('remove')
            ->with($collection);
        $em
            ->expects($this->once())->method('flush');

        $manager->delete($collection);
    }
} 