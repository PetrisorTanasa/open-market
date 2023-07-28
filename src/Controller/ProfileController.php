<?php
// src/Controller/ProfileController.php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/edit", name="profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $profile = $user->getProfile();

        $form = $this->createForm(RegistrationFormType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update profile data and save changes
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully.');

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile", name="profile_show", methods={"GET"})
     */
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }
}
