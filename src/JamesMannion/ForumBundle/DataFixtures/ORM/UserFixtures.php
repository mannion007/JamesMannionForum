<?php
namespace JamesMannion\ForumBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JamesMannion\ForumBundle\Constants\Fixtures;
use JamesMannion\ForumBundle\Entity\User;
use JamesMannion\ForumBundle\Constants\AppConfig;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var User[] $users $i */
        for($i=0; $i<5; $i++) {
            $password = 'password' . $i;
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $users[$i] = new User();
            $users[$i]->setUsername('User '.$i);
            $users[$i]->setEmail('user'.$i.'@'.AppConfig::DOMAIN);
            $users[$i]->setPassword($hashedPassword);
            $users[$i]->setCreated(new \DateTime());
            $users[$i]->setUpdated(new \DateTime());
            $users[$i]->setMemorableQuestion(
                $this->getReference('memorableQuestion-' . rand(0,5))
            );
            $users[$i]->setMemorableAnswer('Memorable Answer '.$i);
            $users[$i]->setIsActive(true);

            $manager->persist($users[$i]);
            $manager->flush();
            $this->addReference('user' . $i, $users[$i]);
        }
    }

    public function getOrder()
    {
        return Fixtures::USER_FIXTURES_ORDER;
    }
}
