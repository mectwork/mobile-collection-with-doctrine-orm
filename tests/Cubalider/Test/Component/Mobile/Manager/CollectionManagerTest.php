<?php

namespace Cubalider\Test\Component\Mobile\Manager;

use Cubalider\Component\Mobile\Model\Collection;
use Cubalider\Component\Mobile\Manager\CollectionManager;
use Cubalider\Test\Component\Mobile\EntityManagerBuilder;
use Doctrine\ORM\EntityManager;

/**
 * @author Miguel Torres <miguel.torres.ss24@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectionManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp()
    {
        $builder = new EntityManagerBuilder();
        $this->em = $builder->createEntityManager(
            array(
                sprintf("%s/../../../../../../src/Cubalider/Component/Mobile/Resources/config/doctrine", __DIR__)
            ),
            array(
                'Cubalider\Component\Mobile\Model\Collection',
            )
        );
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::__construct
     */
    public function testConstructor()
    {
        $manager = new CollectionManager($this->em);

        $this->assertAttributeEquals($this->em, 'em', $manager);
        $this->assertAttributeEquals($this->em->getRepository('Cubalider\Component\Mobile\Model\Collection'), 'repository', $manager);
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::pick
     */
    public function testPick()
    {
        /* Fixtures */

        $collection = new Collection();
        $this->em->persist($collection);

        $this->em->flush();

        /* Test */

        $manager = new CollectionManager($this->em);

        $this->assertEquals($collection, $manager->pick(array('id' => 1)));
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::collect
     */
    public function testCollect()
    {
        /* Fixtures */

        $collection1 = new Collection();
        $this->em->persist($collection1);

        $collection2 = new Collection();
        $this->em->persist($collection2);

        $this->em->flush();

        /* Test */

        $manager = new CollectionManager($this->em);

        $this->assertEquals(array($collection1, $collection2), $manager->collect());
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::add
     */
    public function testAdd()
    {
        /* Fixtures */

        $collection = new Collection();

        /* Test */

        $manager = new CollectionManager($this->em);
        $manager->add($collection);

        $repository = $this->em->getRepository('Cubalider\Component\Mobile\Model\Collection');
        $this->assertEquals(1, count($repository->findAll()));

        $this->assertSame($collection, $manager->pick(array('id' => 1)));
    }

    /**
     * @covers \Cubalider\Component\Mobile\Manager\CollectionManager::delete
     */
    public function testDelete()
    {
        /* Fixtures */

        $collection = new Collection();
        $this->em->persist($collection);

        $this->em->flush();

        /* Test */

        $manager = new CollectionManager($this->em);
        $manager->delete($collection);

        $this->assertNull($manager->pick(array('id' => $collection->getId())));
    }
} 