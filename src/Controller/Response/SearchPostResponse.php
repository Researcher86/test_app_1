<?php

declare(strict_types=1);

namespace App\Controller\Response;

use App\Entity\Post;
use App\Entity\Tag;

class SearchPostResponse
{
    public array $posts;

    public static function fromEntity(array $posts): self
    {
        $response = new self();

        foreach ($posts as $post) {
            $response->posts[] = [
                'id' => (int) $post->getId(),
                'title' => (string) $post->getTitle(),
                'tags' => array_values(array_map(fn (Tag $tag) => $tag->getId(), $post->getTags()->toArray()))
            ];
        }

        return $response;
    }
}