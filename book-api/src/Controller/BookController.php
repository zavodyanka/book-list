<?php
namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BookController
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("/api/books", name="get_all_books", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $books = $this->bookRepository->findAll();
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'author' => $book->getAuthor(),
                'categories' => $book->getCategories(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/books/{id}", name="get_one_book", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $book->getId(),
            'name' => $book->getName(),
            'author' => $book->getAuthor(),
            'categories' => $book->getCategories(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/books/", name="add_book", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);

        $name = $data['name'];
        $author = $data['author'];
        $categories = $data['categories'];

        if (empty($name) || empty($author) || empty($categories)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->bookRepository->save($name, $author, $categories);

        return new JsonResponse(['status' => 'Book has been created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/books/{id}", name="update_books", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ?: $book->setName($data['name']);
        empty($data['author']) ?: $book->setAuthor($data['author']);
        empty($data['categories']) ?: $book->setPhoneNumber($data['categories']);

        $updatedBook = $this->bookRepository->update($book);

        return new JsonResponse($updatedBook->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/api/books/{id}", name="delete_book", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $this->bookRepository->remove($book);

        return new JsonResponse(['status' => 'Customer has been deleted'], Response::HTTP_NO_CONTENT);
    }
}