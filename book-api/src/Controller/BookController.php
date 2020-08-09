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
     * @Route("/books", name="get_all_books", methods={"GET"})
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
     * @Route("/books/{id}", name="get_one_book", methods={"GET"})
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
}