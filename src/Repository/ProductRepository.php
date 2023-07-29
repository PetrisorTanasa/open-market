<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private ManagerRegistry $entityManager;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $registry;
    }

    public function createProduct(Product $product, int $userId)
    {
        $product->setUserId($userId);

        $entityManager = $this->entityManager->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }

    public function readProduct(int $userId, array $orderBy, string $search, int $page, int $limit)
    {
        $entityManager = $this->entityManager->getManager();

        $queryBuilder = $this->createQueryBuilder('p')->andWhere('p.user_id = ' . $userId);
        foreach ($orderBy as $field => $dir) {
            $queryBuilder->orderBy('p.' . $field, $dir);
        }
        if($search != "") {
            $queryBuilder->where('p.product LIKE \'%' . $search . '%\'');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function deleteProduct(int $productId)
    {
        $entityManager = $this->entityManager->getManager();
        $product = $entityManager->getRepository(Product::class)->find($productId);
        $entityManager->remove($product);
        $entityManager->flush();
    }

    public function getCount(int $userId)
    {
        $entityManager = $this->entityManager->getRepository(Product::class)->findBy(["user_id" => $userId]);
        return count($entityManager);
    }
}