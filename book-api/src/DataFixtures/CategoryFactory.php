<?php
namespace App\DataFixtures;


use App\Entity\Category;

class CategoryFactory
{
    public static function category()
    {
        return new Category();
    }
}