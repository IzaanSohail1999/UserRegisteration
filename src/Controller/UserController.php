<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('userRegisteration.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/register", name="user_registration_submit")
     */
    public function registerUserAction(Request $request): Response
    {

        if($request->getMethod() == 'POST') {
            $params = $request->request->all();
            $file = $request->files->get('image'); // Get the uploaded file

            $imageDirectory = $this->getParameter('kernel.project_dir') . '/public/images/user';
            $imageName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($imageDirectory, $imageName);

            $uploadedImagePath = 'images/user/' . $imageName;

            $errors = $this->validateFormData($params);
            if (!empty($errors)) {
                // Handle validation errors and return appropriate response
            }

            $userEntity = new User();
            $userEntity->setEmail($params['email']);
            $userEntity->setPassword($params['password']);
            $userEntity->setRepeatPassword($params['repeat_password']);
            $userEntity->setFullName($params['full_name']);
            $userEntity->setImagePath($uploadedImagePath);

            $em = $this->getDoctrine()->getManager();
            $em->persist($userEntity);
            $em->flush();

            $this->addFlash('success', 'User Added Successfully');

        }

        return $this->redirectToRoute('app_homepage');
    }

    private function validateFormData(array $data): array
    {
        $errors = [];

        if (empty($data['email'])) {
            $errors[] = 'Email is required.';
        }

        return $errors;
    }
}
