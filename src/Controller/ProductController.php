<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
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

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage->getToken();
        $this->user = $this->tokenStorage->getUser();
    }

    #[Route('/product/create', name: 'app_product_create', methods: ['POST'])]
    public function productCreate(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the product to the database or perform other actions
            // For example:
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($product);
            // $entityManager->flush();

            // Redirect to a success page or show a success message
            return $this->redirectToRoute('home');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/read', name: 'app_product_read', methods: ['GET'])]
    public function productRead(): Response
    {
        $data = [
        ['name' => 'John Doe', 'email' => 'john@example.com'],
        ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
        ];
        return new JsonResponse(['data' => $data]);
    }

    #[Route('/product/update', name: 'app_product_update')]
    public function productUpdate(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/delete', name: 'app_product_delete')]
    public function productDelete(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
