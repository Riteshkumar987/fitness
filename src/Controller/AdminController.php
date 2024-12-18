<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/dashboard', name: 'admin_dashboard')]
    public function index(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        // Render the dashboard template and pass user data
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);


            // Set the role based on form input
            $role = $request->request->get('role');
            $user->setRoles([$role === 'admin' ? 'ROLE_ADMIN' : 'ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard');
        }
    
        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(), // Pass the FormView to the template
        ]);
    }
    

    #[Route('/admin/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show($id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(UserType::class, $user, [
        'is_edit' => true, // Indicate that this is an edit form
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'User updated successfully!');

        return $this->redirectToRoute('dashboard');
    }

    return $this->render('admin/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('admin/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard');
    }

    #[Route('/profile', name: 'profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profile(): Response
    {
        $user = $this->getUser(); // Get the currently logged-in user

        return $this->render('admin/profile.html.twig', [
            'user' => $user,
        ]);
    }

}
