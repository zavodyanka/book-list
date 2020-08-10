<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Book::class);
        $this->manager = $manager;
    }

    public function save(Book $book)
    {
        $this->manager->persist($book);
        $this->manager->flush();
    }

    public function update(Book $book): Book
    {
        $this->manager->persist($book);
        $this->manager->flush();

        return $book;
    }

    public function remove(Book $book)
    {
        $this->manager->remove($book);
        $this->manager->flush();
    }
}
