<?php

declare(strict_types=1);

namespace App\Controller\Response;

use App\Entity\Tag;

class CreateTagResponse
{
    public int $id;
    public string $name;

    public static function fromEntity(Tag $tag): self
    {
        $response = new self();
        $response->id = (int) $tag->getId();
        $response->name = (string) $tag->getName();

        return $response;
    }
}