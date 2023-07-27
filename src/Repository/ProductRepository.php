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

    public function readProduct(int $userId, array $orderBy = [])
    {
        $entityManager = $this->entityManager->getManager();
//        $products = $entityManager->getRepository(Product::class)->findBy(['user_id' => $userId]);
//
//        $productArray = [];
//        foreach ($products as $product) {
//            $productArray[] = [
//                'product' => $product->getProduct(),
//                'quantity' => $product->getQuantity(),
//                'price' => $product->getPrice(),
//                'description' => $product->getDescription()
//            ];
//        }
//
//        return $productArray;
        $queryBuilder = $this->createQueryBuilder('p')->andWhere('p.user_id = ' . $userId);

        foreach ($orderBy as $field => $dir) {
            $queryBuilder->orderBy('p.' . $field, $dir);
        }

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        // You can format the result array as needed, such as converting to associative arrays.

        return $result;
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
