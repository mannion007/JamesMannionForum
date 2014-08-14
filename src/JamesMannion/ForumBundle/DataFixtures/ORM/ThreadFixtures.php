<?php
namespace JamesMannion\ForumBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JamesMannion\ForumBundle\Constants\Fixtures;
use JamesMannion\ForumBundle\Entity\Post;
use JamesMannion\ForumBundle\Entity\Thread;

class ThreadFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $count = 1;

        for($i=0; $i<12; $i++) {
            for($j=0; $j<20; $j++) {
                $thread = new Thread();
                $thread->setTitle('My Lovely Thread #' . $count);
                $thread->setRoom($this->getReference('room' . $i));

                for($k=0; $k<15; $k++) {
                    $post = new Post();
                    $post->setAuthor($this->getReference('user' . (rand(0,4))));
                    $post->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean congue,
                    libero sed aliquam dictum, leo arcu molestie ligula, hendrerit consectetur tellus felis at eros.
                    Integer vitae tellus euismod, vulputate leo id, mattis metus.');
                    $post->setPrimaryPost(true);
                    $thread->addPost($post);
                }

                $manager->persist($thread);
                $manager->flush();
                $this->addReference('thread' . $count, $thread);
                $count ++;
            }

        }
    }

    public function getOrder()
    {
        return Fixtures::THREAD_FIXTURES_ORDER;
    }
}
