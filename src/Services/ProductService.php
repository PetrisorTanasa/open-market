<?php
namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(
        ManagerRegistry $entityManager
    )
    {
        $this->productRepository = new ProductRepository($entityManager);
    }

    public function createProduct(Product $product, int $userId)
    {
        $this->productRepository->createProduct($product, $userId);
    }

    public function readProduct(int $userId, array $orderBy, string $search)
    {
        return $this->productRepository->readProduct($userId, $orderBy, $search);
    }

    public function deleteProduct()
    {
    }
}