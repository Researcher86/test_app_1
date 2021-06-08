<?php

declare(strict_types=1);

namespace App\Controller\Request;

use App\Controller\Common\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTagRequest implements RequestInterface
{
    #[Assert\NotBlank(message: "Please enter an name", normalizer: "trim")]
    public string $name;
}