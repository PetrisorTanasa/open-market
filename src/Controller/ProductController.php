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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        $pageNumber = $request->query->getInt('page', 1);
        $recordsPerPage = $request->query->getInt('records_per_page', 10);

        $orderBy = [];
        foreach ($order as $orderItem) {
            $columnIdx = $orderItem['column'] ?? null;
            $dir = $orderItem['dir'] ?? 'asc';

            if (isset($columns[$columnIdx]['data']) && in_array($dir, ['asc', 'desc'])) {
                $column = $columns[$columnIdx]['data'];
                $orderBy[$column] = $dir;
            }
        }
        $totalCount = $this->productService->getCount(
            $this->user->getId()
        );

        $data = $this->productService->readProduct(
            $this->user->getId(),
            $orderBy ?? [],
            $searchTerm ?? "",
            $pageNumber,
            $recordsPerPage
        );

        $paginatedData = array_slice($data, ($pageNumber - 1) * $recordsPerPage, $pageNumber * $recordsPerPage);

        return $this->json([
            'data' => $paginatedData,
            'totalRecords' => $totalCount,
            'recordsTotal' => $totalCount,
            'recordsFiltered' => count($data), // You can update this if you perform filtering
            'totalPages' => ceil($totalCount/$recordsPerPage),
            'draw' => $request->query->getInt('draw', 1),
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

    #[Route('/product/{id}/delete', name: 'app_product_delete')]
    public function productDelete(Request $request, $id)
    {
        var_dump($id);
        $this->productService->deleteProduct($id);
        return $this->redirectToRoute('home');
    }
}
