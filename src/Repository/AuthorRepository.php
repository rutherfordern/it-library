<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\Author\UpdateAuthorDto;
use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findOneByFullName(UpdateAuthorDto $updateAuthorDto): ?Author
    {
        return $this->findOneBy([
            'firstName' => $updateAuthorDto->getFirstName(),
            'lastName' => $updateAuthorDto->getLastName(),
        ]);
    }

    public function saveAndFlush(Author $author): void
    {
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();
    }

    public function delete(Author $author): void
    {
        $this->getEntityManager()->remove($author);
        $this->getEntityManager()->flush();
    }
}
