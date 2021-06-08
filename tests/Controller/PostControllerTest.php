<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\PostController;
use App\DataFixtures\PostFixtures;
use App\DataFixtures\TagFixtures;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testGetOne()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/post/' . PostFixtures::POST_1_ID,
            [],
            [],
            [],
            ''
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals('Post 1', \json_decode($client->getResponse()->getContent(), true)['title']);
    }

    public function testSearchByOneTag()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/posts',
            [],
            [],
            [],
            \json_encode(['tags' => [TagFixtures::TAG_1_ID]])
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals(2, count(\json_decode($client->getResponse()->getContent(), true)['posts']));
    }

    public function testSearchByTwoTags()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/posts',
            [],
            [],
            [],
            \json_encode(['tags' => [TagFixtures::TAG_1_ID, TagFixtures::TAG_2_ID]])
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals(1, count(\json_decode($client->getResponse()->getContent(), true)['posts']));
    }

    public function testSearchNotFoundByAllTags()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/posts',
            [],
            [],
            [],
            \json_encode(['tags' => [TagFixtures::TAG_1_ID, TagFixtures::TAG_3_ID]])
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEmpty(\json_decode($client->getResponse()->getContent(), true));
    }

    public function testCreate()
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->beginTransaction();

        $client->request(
            'POST',
            '/api/post',
            [],
            [],
            [],
            \json_encode(['title' => 'New post', 'tags' => [TagFixtures::TAG_1_ID, TagFixtures::TAG_3_ID]])
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals('New post', \json_decode($client->getResponse()->getContent(), true)['title']);

        $em->rollback();
    }

    public function testUpdate()
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->beginTransaction();

        $client->request(
            'PUT',
            '/api/post',
            [],
            [],
            [],
            \json_encode(['id' => PostFixtures::POST_1_ID, 'title' => 'Update title', 'tags' => [TagFixtures::TAG_2_ID]])
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals('Update title', \json_decode($client->getResponse()->getContent(), true)['title']);
        $this->assertEquals([TagFixtures::TAG_2_ID], \json_decode($client->getResponse()->getContent(), true)['tags']);

        $em->rollback();
    }

    public function testDelete()
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->beginTransaction();

        $client->request(
            'DELETE',
            '/api/post/' . PostFixtures::POST_1_ID,
            [],
            [],
            [],
            ''
        );

        $this->assertResponseIsSuccessful();
        $this->assertEmpty($client->getResponse()->getContent());

        $em->rollback();
    }
}
