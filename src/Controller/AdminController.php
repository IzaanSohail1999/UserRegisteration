<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        return $this->render('adminPanel.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
        ]);
    }
}
