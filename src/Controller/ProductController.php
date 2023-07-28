<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Services\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $tokenStorage;

    private $user;

    private ProductService $productService;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ProductService $productService,
        ManagerRegistry $managerRegistry
    )
    {
        $this->tokenStorage = $tokenStorage->getToken();
        $this->user = $this->tokenStorage->getUser();
        $this->productService = new ProductService($managerRegistry);
    }

    #[Route('/product/create', name: 'app_product_create', methods: ['GET', 'POST'])]
    public function productCreate(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->productService->createProduct(
                $product,
                $this->user->getId()
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/read', name: 'app_product_read', methods: ['GET'])]
    public function productRead(Request $request): Response
    {
        $allParameters = $request->query->all();

        $columns = $allParameters['columns'] ?? [];
        $order = $allParameters['order'] ?? [];
        $searchTerm = $allParameters['search']['value'] ?? null;

        $orderBy = [];
        foreach ($order as $orderItem) {
            $columnIdx = $orderItem['column'] ?? null;
            $dir = $orderItem['dir'] ?? 'asc';

            if (isset($columns[$columnIdx]['data']) && in_array($dir, ['asc', 'desc'])) {
                $column = $columns[$columnIdx]['data'];
                // Map the column data to the actual entity field names as needed
                // For example, if your column 'data' is 'product', but the actual entity field is 'name',
                // then you should map it here.
                $orderBy[$column] = $dir;
            }
        }

        $data = $this->productService->readProduct(
            $this->user->getId(),
            $orderBy ?? [],
            $searchTerm ?? ""
        );

        return $this->json([
            'data' => $data,
        ]);
    }

    #[Route('/product/{id}/update', name: 'app_product_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('product/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/delete', name: 'app_product_delete')]
    public function productDelete(): Response
    {
        return $this->render('product/delete.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
