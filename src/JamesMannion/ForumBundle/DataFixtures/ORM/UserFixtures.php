<?php
namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JamesMannion\ForumBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    private $order = 2;

    private $forenames = array('Homer', 'Bart', 'Moe', 'Barney', 'Otto');

    private $surnames = array('Simpson', 'Sislack', 'Gumble', 'Bouvier', 'Van Houten');

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        for($i=0; $i<5; $i++) {
            $userManager = $this->container->get('fos_user.user_manager');

            /** @var User $user */
            $user = $userManager->createUser();
            $user->setUsername('user' . $i);
            $user->setForename($this->forenames[$i]);
            $user->setSurname($this->surnames[$i]);
            $user->setDob(new \DateTime());
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setPlainPassword('password' . $i);
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER'));

            $userManager->updateUser($user);

            $this->addReference('user' . $i, $user);
        }
    }

    public function getOrder()
    {
        return $this->order;
    }
}