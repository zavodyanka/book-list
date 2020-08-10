<?php
namespace App\Service;

use App\Entity\Book as EntityBook;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Book
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository
    ) {

        $this->bookRepository = $bookRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getList()
    {
        $books = $this->bookRepository->findAll();
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'author' => $book->getAuthor(),
                'categories' => $this->convertToArray($book->getCategories()),
            ];
        }

        return $data;
    }

    public function getOneById(int $id): array
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        return [
            'id' => $book->getId(),
            'name' => $book->getName(),
            'author' => $book->getAuthor(),
            'categories' => $this->convertToArray($book->getCategories()),
        ];
    }

    private function convertToArray($categories)
    {
        $bookCategories = [];

        foreach ($categories as $category) {
            $bookCategories[] = $category->toArray();
        }

        return $bookCategories;
    }

    public function add(array $data): string
    {
        if (empty($data['name']) || empty($data['author'])) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $book = new EntityBook();

        $book
            ->setName($data['name'])
            ->setAuthor($data['author']);
        $this->addCategories($book, $data['categories']);

        $this->bookRepository->save($book);

        return 'Book has been created!';
    }

    public function update($id, $data): array
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);
        empty($data['name']) ?: $book->setName($data['name']);
        empty($data['author']) ?: $book->setAuthor($data['author']);
        $this->updateCategories($book, $data['categories']);
        $updatedBook = $this->bookRepository->update($book);

        return $updatedBook->toArray();
    }

    private function updateCategories(EntityBook $book, array $newCategories): void
    {
        $categories = $book->getCategories();

        foreach ($categories as $category) {
            $book->removeCategory($category);
        }

        $this->addCategories($book, $newCategories);
    }

    private function addCategories(EntityBook $book, array $names)
    {
        $categories = $this->categoryRepository->findBy(
            ['name' => $names]
        );

        foreach ($categories as $category) {
            $book->addCategory($category);
        }
    }

    public function remove(int $id): string
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $this->bookRepository->remove($book);

        return 'Book has been deleted';
    }
}