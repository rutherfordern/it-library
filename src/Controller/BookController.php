<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Book\CreateBookDto;
use App\DTO\Book\UpdateBookDto;
use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Form\Book\BookCreateType;
use App\Form\Book\BookUpdateType;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private BookService $bookService)
    {
    }

    #[Route('/books', name: 'book_index', methods: ['GET'])]
    public function index(): Response
    {
        $books = $this->bookService->getAllBooks();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/books/create', name: 'book_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $createBookDto = new CreateBookDto();

        $form = $this->createForm(BookCreateType::class, $createBookDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $this->bookService->createBook($createBookDto);
            $bookId = $book->getId();

            return $this->redirectToRoute('book_update', ['id' => $bookId]);
        }

        return $this->render('book/new.html.twig', [
            'book' => $createBookDto,
            'form' => $form,
        ]);
    }

    #[Route('/books/{id}', name: 'book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/books/{id}/edit', name: 'book_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Book $book): Response
    {
        $updateBookDto = new UpdateBookDto();

        $fillUpdateBookDto = $updateBookDto::fillDataFromBook($book);

        $form = $this->createForm(BookUpdateType::class, $fillUpdateBookDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookId = $book->getId();

            try {
                $this->bookService->updateBook($fillUpdateBookDto, $bookId);

                return $this->redirectToRoute('book_show', ['id' => $bookId]);
            } catch (BookAlreadyExistsException $e) {
                $form->addError(new FormError($e->getMessage()));
            } catch (\Exception $e) {
            }
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/books/{id}/delete', name: 'book_delete', methods: ['DELETE'])]
    public function delete(Book $book): Response
    {
        $this->bookService->deleteBook($book);

        return $this->redirectToRoute('book_index');
    }
}
