<?php

declare(strict_types=1);

namespace App\Controller\Request;

use App\Controller\Common\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostRequest implements RequestInterface
{
    #[Assert\Positive(message: "Id value should be positive.")]
    #[Assert\Type("integer")]
    public int $id;

    #[Assert\NotBlank(message: "Please enter an title", normalizer: "trim")]
    public string $title;

    /**
     * @Assert\All({
     *     @Assert\NotBlank(normalizer="trim"),
     *     @Assert\Type("integer")
     * })
     */
    public array $tags = [];
}