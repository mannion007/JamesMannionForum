<?php
namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JamesMannion\ForumBundle\Entity\Building;

class RoomFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    private $order = 2;

    public function load(ObjectManager $manager)
    {
        for($i=0; $i<5; $i++) {

            $building = new Building();
            $building->setName('My Lovely Building #' . $i);
            $building->setSequence($i);
            $building->setHidden(false);
            $manager->persist($building);
            $manager->flush();

            $this->addReference('building' . $i, $building);
        }
    }

    public function getOrder()
    {
        return $this->order;
    }
}