<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const TAG_1_ID = 1;
    public const TAG_2_ID = 2;
    public const TAG_3_ID = 3;

    public function load(ObjectManager $manager)
    {
        $tag1 = new Tag();
        $tag1->setName('Tag 1');
        $manager->persist($tag1);
        $this->addReference(self::TAG_1_ID, $tag1);

        $tag2 = new Tag();
        $tag2->setName('Tag 2');
        $manager->persist($tag2);
        $this->addReference(self::TAG_2_ID, $tag2);

        $tag3 = new Tag();
        $tag3->setName('Tag 3');
        $manager->persist($tag3);
        $this->addReference(self::TAG_3_ID, $tag3);

        $manager->flush();
    }
}
