<?php
namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JamesMannion\ForumBundle\Entity\Room;


class RoomFixtures extends AbstractFixture implements FixtureInterface
{

    private $order = 1;

    public function load(ObjectManager $manager)
    {
        for($i=1; $i<12; $i++) {

            $room = new Room();
            $room->setName('My Lovely Room #' . $i);
            $room->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean congue,
            libero sed aliquam dictum, leo arcu molestie ligula, hendrerit consectetur tellus felis at eros.
            Integer vitae tellus euismod, vulputate leo id, mattis metus.');

            $manager->persist($room);
            $manager->flush();

            $this->addReference('room' . $i, $room);
        }
    }

    public function getOrder()
    {
        return $this->order;
    }
}