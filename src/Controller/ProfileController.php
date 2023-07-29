<?php
// src/Controller/ProfileController.php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Form\RegistrationFormType;
use App\Services\ProductService;
use App\Services\ProfileService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class ProfileController extends AbstractController
{
    private ProfileService $profileService;

    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        $this->profileService = new ProfileService($managerRegistry);
    }

    #[Route('/profile/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $allParameters = $request->query->all();
        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->profileService->editProfile($user);
            $this->addFlash('success', 'Profile updated successfully.');

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile', name: 'profile_show', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/language_update', name: 'language_update', methods: ['GET'])]
    public function langUpdate(Request $request)
    {
        $allParameters = $request->query->all();
        $user = $this->getUser();
        try {
        $language = $allParameters['language'];
    } catch (\Exception $e) {
        $language = 'en';
    }
        $session = $request->getSession();
        $session->set('user_locale_language', $language);

        $user->setLanguage($language);
        $this->profileService->editProfile($user);
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}
