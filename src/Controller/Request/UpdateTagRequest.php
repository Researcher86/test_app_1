<?php

declare(strict_types=1);

namespace App\Controller\Request;

use App\Controller\Common\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTagRequest implements RequestInterface
{
    #[Assert\Positive(message: "Id value should be positive.")]
    #[Assert\Type("integer")]
    public int $id;

    #[Assert\NotBlank(message: "Please enter an name", normalizer: "trim")]
    public string $name;
}