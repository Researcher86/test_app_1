<?php

namespace App\Controller;

use App\Controller\Request\CreateTagRequest;
use App\Controller\Request\UpdateTagRequest;
use App\Controller\Response\CreateTagResponse;
use App\Controller\Response\UpdateTagResponse;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TagController extends AbstractController
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    #[Route('/tag', name: 'create_tag', methods: ['POST'])]
    public function create(CreateTagRequest $request): Response
    {
        $tag = (new Tag())->setName($request->name);
        $this->tagRepository->save($tag);

        return $this->json(CreateTagResponse::fromEntity($tag), Response::HTTP_CREATED);
    }

    #[Route('/tag', name: 'update_tag', methods: ['PUT'])]
    public function update(UpdateTagRequest $request): Response
    {
        $tag = ($this->tagRepository->find($request->id))->setName($request->name);
        $this->tagRepository->save($tag);

        return $this->json(UpdateTagResponse::fromEntity($tag), Response::HTTP_OK);
    }
}
