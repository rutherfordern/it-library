<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Book\CreateBookDto;
use App\DTO\Book\UpdateBookDto;
use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Repository\BookRepository;

class BookService
{
    public function __construct(private BookRepository $bookRepository, private AuthorService $authorService)
    {
    }

    public function getAllBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function createBook(CreateBookDto $createBookDto): Book
    {
        $authorArray = $this->authorService->getOrCreateAuthors($createBookDto->getAuthors());

        $book = new Book();

        $book->setTitle($createBookDto->getTitle());
        $book->addAuthors($authorArray);

        $this->bookRepository->saveAndFlush($book);

        return $book;
    }

    public function updateBook(UpdateBookDto $updateBookDto, int $id): void
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $this->checkBookUnique($book, $updateBookDto);

        $this->updateBookFromDto($book, $updateBookDto);
    }

    public function deleteBook(Book $book): void
    {
        $this->bookRepository->delete($book);
    }

    private function updateBookFromDto(Book $book, UpdateBookDto $updateBookDto): void
    {
        $book->setTitle($updateBookDto->getTitle());
        $book->setYear($updateBookDto->getYear());
        $book->setIsbn($updateBookDto->getIsbn());
        $book->setPageCount($updateBookDto->getPageCount());
        $book->setImageFile($updateBookDto->getImageFile());
        $book->setImageName($updateBookDto->getImageName());

        $this->bookRepository->saveAndFlush($book);
    }

    private function checkBookUnique(Book $book, UpdateBookDto $updateBookDto): void
    {
        $existingBookByTitleAndIsbn = $this->bookRepository->findOneByTitleAndIsbn($updateBookDto);
        $existingBookByTitleAndYear = $this->bookRepository->findOneByTitleAndYear($updateBookDto);

        if ($existingBookByTitleAndIsbn && $existingBookByTitleAndIsbn->getId() !== $book->getId()) {
            throw new BookAlreadyExistsException('Книга с таким названием и ISBN уже существует.');
        }

        if ($existingBookByTitleAndYear && $existingBookByTitleAndYear->getId() !== $book->getId()) {
            throw new BookAlreadyExistsException('Книга с таким названием и годом издания уже существует.');
        }
    }
}
