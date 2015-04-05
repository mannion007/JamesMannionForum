<?php

namespace JamesMannion\ForumBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JamesMannion\ForumBundle\Constants\Fixtures;
use JamesMannion\ForumBundle\Entity\Room;

class RoomFixtures extends AbstractFixture implements OrderedFixtureInterface
{


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for($i=0; $i<12; $i++) {

            $room = new Room();
            $room->setBuilding($this->getReference('building' . (rand(0,4))));
            $room->setName('My Lovely Room #' . $i);
            $room->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean congue, libero.');

            $manager->persist($room);
            $manager->flush();

            $this->addReference('room' . $i, $room);
        }
    }

    public function getOrder()
    {
        return Fixtures::ROOM_FIXTURES_ORDER;
    }
}

