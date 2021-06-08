<?php

declare(strict_types=1);

namespace App\Controller\Response;

use App\Entity\Post;
use App\Entity\Tag;

class UpdatePostResponse
{
    public int $id;
    public string $title;
    public array $tags;

    public static function fromEntity(Post $post): self
    {
        $response = new self();
        $response->id = (int) $post->getId();
        $response->title = (string) $post->getTitle();
        $response->tags = array_values(array_map(fn (Tag $tag) => $tag->getId(), $post->getTags()->toArray()));

        return $response;
    }
}