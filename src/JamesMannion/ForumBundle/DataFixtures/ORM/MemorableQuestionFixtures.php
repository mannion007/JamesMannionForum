<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 01/03/14
 * Time: 20:54
 */

namespace JamesMannion\ForumBundle\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use JamesMannion\ForumBundle\Constants\Fixtures;
use JamesMannion\ForumBundle\Entity\MemorableQuestion;

class MemorableQuestionFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $memorableQuestionExamples = array(
            "What was the name of your first pet?",
            "What is your Mother's maiden name?",
            "What is your Father's middle name? ",
            "What was the registration number of your first car?",
            "What was your favourite toy as a child?",
            "What is your favorite icecream flavour?",
        );
        $i = 0;
        foreach ($memorableQuestionExamples as $memorableQuestionExample) {
            $memorableQuestion = new MemorableQuestion();
            $memorableQuestion->setQuestion($memorableQuestionExample);
            $manager->persist($memorableQuestion);
            $manager->flush();
            $this->addReference('memorableQuestion-' . $i, $memorableQuestion);
            $i++;
        }

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return Fixtures::MEMORABLE_QUESTION_FIXTURES_ORDER;
    }
}
