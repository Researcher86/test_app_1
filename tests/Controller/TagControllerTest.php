<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\TagFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->beginTransaction();

        $client->request(
            'POST',
            '/api/tag',
            [],
            [],
            [],
            \json_encode(['name' => 'New tag'])
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals('New tag', \json_decode($client->getResponse()->getContent(), true)['name']);

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
            '/api/tag',
            [],
            [],
            [],
            \json_encode(['id' => TagFixtures::TAG_1_ID, 'name' => 'Update name'])
        );

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals('Update name', \json_decode($client->getResponse()->getContent(), true)['name']);

        $em->rollback();
    }
}
