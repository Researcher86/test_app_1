<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\CreatePostRequest;
use App\Controller\Request\SearchPostRequest;
use App\Controller\Request\UpdatePostRequest;
use App\Controller\Response\CreatePostResponse;
use App\Controller\Response\SearchPostResponse;
use App\Controller\Response\ShowPostResponse;
use App\Controller\Response\UpdatePostResponse;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PostController extends AbstractController
{
    private PostRepository $postRepository;
    private TagRepository $tagRepository;

    public function __construct(PostRepository $postRepository, TagRepository $tagRepository)
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
    }

    #[Route('/posts', name: 'search_post', methods: ['POST'])]
    public function search(SearchPostRequest $request): Response
    {
        $posts = $this->postRepository->search($request->tags);

        return $this->json(SearchPostResponse::fromEntity($posts), Response::HTTP_OK);
    }

    #[Route('/post/{id}', name: 'get_post', methods: ['GET'])]
    public function getOne(int $id): Response
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json(ShowPostResponse::fromEntity($post), Response::HTTP_OK);
    }

    #[Route('/post', name: 'create_post', methods: ['POST'])]
    public function create(CreatePostRequest $request): Response
    {
        $tags = $this->tagRepository->findBy(['id' => $request->tags]);

        $post = new Post();
        $post->setTitle($request->title);
        $post->addTags($tags);

        return $this->json(CreatePostResponse::fromEntity($post), Response::HTTP_CREATED);
    }

    #[Route('/post', name: 'update_post', methods: ['PUT'])]
    public function update(UpdatePostRequest $request): Response
    {
        $tags = $this->tagRepository->findBy(['id' => $request->tags]);

        $post = $this->postRepository->find($request->id);
        if (!$post) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $post->setTitle($request->title);
        $post->removeTags();
        $post->addTags($tags);

        $this->postRepository->save($post);

        return $this->json(UpdatePostResponse::fromEntity($post), Response::HTTP_OK);
    }

    #[Route('/post/{id}', name: 'delete_post', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $this->postRepository->delele($post);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
