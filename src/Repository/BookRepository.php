<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\Book\UpdateBookDto;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findOneByTitleAndIsbn(UpdateBookDto $updateBookDto): ?Book
    {
        return $this->findOneBy([
            'title' => $updateBookDto->getTitle(),
            'isbn' => $updateBookDto->getIsbn(),
        ]);
    }

    public function findOneByTitleAndYear(UpdateBookDto $updateBookDto): ?Book
    {
        return $this->findOneBy([
            'title' => $updateBookDto->getTitle(),
            'year' => $updateBookDto->getYear(),
        ]);
    }

    public function save(Book $book): void
    {
        $this->getEntityManager()->persist($book);
    }

    public function saveAndFlush(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function delete(Book $book): void
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }
}
