<?php

declare(strict_types=1);

namespace App\DTO\Author;

use App\Entity\Author;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateAuthorDto
{
    #[Assert\NotBlank(message: 'Введите имя автора')]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'Введите фамилию автора')]
    private ?string $lastName = null;

    public static function fillDataFromAuthor(Author $author): self
    {
        $updateAuthorRequest = new self();

        $updateAuthorRequest->firstName = $author->getFirstName();
        $updateAuthorRequest->lastName = $author->getLastName();

        return $updateAuthorRequest;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }
}
