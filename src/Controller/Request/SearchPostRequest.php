<?php

declare(strict_types=1);

namespace App\Controller\Request;

use App\Controller\Common\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchPostRequest implements RequestInterface
{
    /**
     * @Assert\All({
     *     @Assert\NotBlank(normalizer="trim"),
     *     @Assert\Type("integer")
     * })
     */
    public array $tags = [];
}