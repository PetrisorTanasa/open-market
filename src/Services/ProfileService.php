<?php
namespace App\Services;

use App\Entity\Product;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

class ProfileService
{
    private UserRepository $userRepository;

    public function __construct(
        ManagerRegistry $entityManager
    )
    {
        $this->userRepository = new UserRepository($entityManager);
    }

    public function editProfile(UserInterface $user)
    {
        $this->userRepository->editProfile($user);
    }
}