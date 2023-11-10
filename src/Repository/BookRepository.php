<?php

namespace App\Repository;

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
    public function searchById($ref) // using dql
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT b FROM App\Entity\Book b WHERE b.ref = :ref')
            ->setParameter('ref', $ref);
        return $query->getResult();
    }
    public function before2023andjoin() // using dql
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT b FROM App\Entity\Book b JOIN b.Author a WHERE b.publicationdate < :start_date AND a.nb_books > 20')
            ->setParameter('startdate', new \DateTime('2023-01-01'));
        return $query->getResult();
    }
    public function findBooksBefore2023()
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->Join('b.Author', 'a')
            ->where('b.publicationdate < :year2023')
            ->andWhere('a.nb_books > 1')
            ->setParameter('year2023', new \DateTime('2023-01-01'));
        return $queryBuilder->getQuery()->getResult();
    }



}
