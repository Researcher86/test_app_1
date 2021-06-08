<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public const POST_1_ID = 1;

    public function load(ObjectManager $manager)
    {
        $post1 = new Post();
        $post1->setTitle('Post 1');
        $post1->addTag($this->getReference(TagFixtures::TAG_1_ID));

        $manager->persist($post1);

        $post2 = new Post();
        $post2->setTitle('Post 2');
        $post2->addTag($this->getReference(TagFixtures::TAG_1_ID));
        $post2->addTag($this->getReference(TagFixtures::TAG_2_ID));

        $manager->persist($post2);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class
        ];
    }
}
