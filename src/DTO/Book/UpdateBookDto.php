<?php

declare(strict_types=1);

namespace App\DTO\Book;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
class UpdateBookDto
{
    #[Assert\NotBlank(message: 'Введите название книги')]
    private string $title;

    #[Assert\Range(
        min: 1800,
        max: 2099
    )]
    private ?int $year = null;

    #[Assert\Length(
        min: 10,
        max: 13,
        minMessage: 'ISBN должен содержать не менее 10 символов',
        maxMessage: 'ISBN должен содержать не более 13 символов'
    )]
    private ?string $isbn = null;

    #[Assert\Range(min: 1, minMessage: 'Количество страниц должно быть не менее 1')]
    private ?int $pageCount = null;

    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'book_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    public static function fillDataFromBook(Book $book): self
    {
        $updateBookRequest = new self();

        $updateBookRequest->title = $book->getTitle();
        $updateBookRequest->year = $book->getYear();
        $updateBookRequest->isbn = $book->getIsbn();
        $updateBookRequest->pageCount = $book->getPageCount();
        $updateBookRequest->imageFile = $book->getImageFile();
        $updateBookRequest->imageName = $book->getImageName();

        return $updateBookRequest;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): void
    {
        $this->year = $year;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(?int $pageCount): void
    {
        $this->pageCount = $pageCount;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function setImageFile(File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
}
