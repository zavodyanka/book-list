<?php
namespace App\Tests;

use App\Entity\Book;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class AuthorNameTest extends TestCase
{
    private $book;

    protected function setUp(): void
    {
        $category = new Category();
        $category->setName("Test category");

        $this->book = new Book();
        $this->book->setName("Test Book");
        $this->book->addCategory($category);
    }

    /**
     * @dataProvider provideData
     */
    public function testCorrectAuthorName($input, $expectedResult)
    {
        $this->book->setAuthor($input);
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $errors = $validator->validate($this->book);
        $this->assertCount($expectedResult, $errors);
    }

    public function provideData()
    {
        return [
            ['Tom Clancy', 0,],
            ['Tom Clancy1', 1,],
            ['TomClancy', 1,],
            ['tom Clancy', 1,],
            ['Tom clancy', 1,],
            ['tom clancy', 1,],
        ];
    }
}