<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Author\UpdateAuthorDto;
use App\Entity\Author;
use App\Exception\AuthorAlreadyExistsException;
use App\Repository\AuthorRepository;

class AuthorService
{
    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    public function getAllAuthors(): array
    {
        return $this->authorRepository->findAll();
    }

    public function updateAuthor(UpdateAuthorDto $updateAuthorDto, int $id): void
    {
        $author = $this->authorRepository->findOneBy(['id' => $id]);

        $this->checkAuthorUnique($author, $updateAuthorDto);

        $author->setFirstName($updateAuthorDto->getFirstName());
        $author->setLastName($updateAuthorDto->getLastName());

        $this->authorRepository->saveAndFlush($author);
    }

    public function getOrCreateAuthors(string $authors): array
    {
        $authorNames = array_map('trim', explode(',', $authors));

        $authorsArray = [];

        foreach ($authorNames as $authorName) {
            $author = $this->getOrCreateOneAuthor($authorName);

            $authorsArray[] = $author;
        }

        return $authorsArray;
    }

    public function getOrCreateOneAuthor(string $authors): Author
    {
        [$authorFirstName, $authorLastName] = array_map('trim', explode(' ', $authors, 2));

        $author = $this->authorRepository->findOneBy(['firstName' => $authorFirstName, 'lastName' => $authorLastName]);

        if (!$author) {
            $author = new Author();
            $author->setFirstName($authorFirstName);
            $author->setLastName($authorLastName);
            $this->authorRepository->saveAndFlush($author);
        }

        return $author;
    }

    public function deleteAuthor(Author $author): void
    {
        $this->authorRepository->delete($author);
    }

    private function checkAuthorUnique(Author $author, UpdateAuthorDto $updateAuthorDto): void
    {
        $existingAuthorByFullName = $this->authorRepository->findOneByFullName($updateAuthorDto);

        if ($existingAuthorByFullName && $existingAuthorByFullName->getId() !== $author->getId()) {
            throw new AuthorAlreadyExistsException('Автор с таким ФИО уже существует.');
        }
    }
}
