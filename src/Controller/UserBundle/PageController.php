<?php
namespace App\Controller\UserBundle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Contact;

class PageController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        
        return $this->render('index.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }

    #[Route('/course', name: 'course')]
    public function course(): Response
    {
        return $this->render('course.html.twig');
    }

    #[Route('/course-single', name: 'course-single')]
    public function courseSingle(): Response
    {
        return $this->render('course-single.html.twig');
    }

    #[Route('/services', name: 'services')]
    public function services(): Response
    {
        return $this->render('service.html.twig');
    }

    #[Route('/blog', name: 'blog')]
    public function blog(): Response
    {
        return $this->render('blog.html.twig');
    }

    #[Route('/blog-single', name: 'blog-single')]
    public function blogSingle(): Response
    {
        return $this->render('blog-single.html.twig');
    }

    #[Route('/blog-sidebar', name: 'blog-sidebar')]
    public function blogSidebar(): Response
    {
        return $this->render('blog-sidebar.html.twig');
    }

    #[Route('/trainer', name: 'trainer')]
    public function trainer(): Response
    {
        return $this->render('trainer.html.twig');
    }

    #[Route('/pricing', name: 'pricing')]
    public function pricing(): Response
    {
        return $this->render('pricing.html.twig');
    }


    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }

    #[Route('/equipment', name: 'equipment')]
    public function equipment(): Response
    {
        return $this->render('equipment.html.twig');
    }

    #[Route('/message', name: 'message')]
    public function message(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $name = $request->request->get('user_name');
        $email = $request->request->get('user_email');
        $messageContent = $request->request->get('user_message');

        $message = new Contact();
        $message->setUserId($user->getId());
        $message->setName($name);
        $message->setEmail($email);
        $message->setMessage($messageContent);
        

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json(['status' => 'success', 'message' => 'Message sent successfully']);
    }
 

    
}

