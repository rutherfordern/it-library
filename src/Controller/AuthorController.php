<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Author\UpdateAuthorDto;
use App\Entity\Author;
use App\Exception\AuthorAlreadyExistsException;
use App\Form\Author\AuthorUpdateType;
use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    #[Route('/authors', name: 'author_index', methods: ['GET'])]
    public function index(): Response
    {
        $authors = $this->authorService->getAllAuthors();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/authors/{id}', name: 'author_show', methods: ['GET'])]
    public function show(Author $author): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/author/{id}/edit', name: 'author_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Author $author): Response
    {
        $updateAuthorDto = new UpdateAuthorDto();

        $fillUpdateAuthorDto = $updateAuthorDto::fillDataFromAuthor($author);

        $form = $this->createForm(AuthorUpdateType::class, $fillUpdateAuthorDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorId = $author->getId();

            try {
                $this->authorService->updateAuthor($fillUpdateAuthorDto, $authorId);

                return $this->redirectToRoute('author_show', ['id' => $authorId]);
            } catch (AuthorAlreadyExistsException $e) {
                $form->addError(new FormError($e->getMessage()));
            } catch (\Exception $e) {
            }
        }

        return $this->render('author/edit.html.twig', [
            'book' => $author,
            'form' => $form,
        ]);
    }

    #[Route('/authors/{id}/delete', name: 'author_delete', methods: ['DELETE'])]
    public function delete(Author $author): Response
    {
        $this->authorService->deleteAuthor($author);

        return $this->redirectToRoute('author_index');
    }
}
