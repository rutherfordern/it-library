<?php

declare(strict_types=1);

namespace App\DTO\Book;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBookDto
{
    #[Assert\NotBlank(message: 'Введите название книги')]
    private string $title;

    #[Assert\NotBlank(message: 'Укажите автора')]
    private string $authors;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthors(): string
    {
        return $this->authors;
    }

    public function setAuthors(string $authors): void
    {
        $this->authors = $authors;
    }
}
