<?php
namespace App\Controller;

use App\Service\Book;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Book
     */
    private $service;

    public function __construct(
        Book $service,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * @Route("/api/books", name="get_all_books", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        try {
            return new JsonResponse(
                $this->service->getList(),
                Response::HTTP_OK
            );
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());

            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_OK);
        }

    }

    /**
     * @Route("/api/books/{id}", name="get_one_book", methods={"GET"})
     */
    public function get(int $id): JsonResponse
    {
        try {
            return new JsonResponse($this->service->getOneById($id), Response::HTTP_OK);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());

            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_OK);
        }
    }

    /**
     * @Route("/api/books", name="add_book", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $data = \json_decode($request->getContent(), true);

            return new JsonResponse(
                ['status' => $this->service->add($data)],
                Response::HTTP_CREATED
            );
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());

            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_OK);
        }
    }

    /**
     * @Route("/api/books/{id}", name="update_books", methods={"PUT"})
     */
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            return new JsonResponse(
                $this->service->update($id, $data),
                Response::HTTP_OK
            );
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());

            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_OK);
        }
    }

    /**
     * @Route("/api/books/{id}", name="delete_book", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        try {
            return new JsonResponse(
                ['status' => $this->service->remove($id)],
                Response::HTTP_NO_CONTENT
            );
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());

            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_OK);
        }
    }
}