<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\ContactAgentNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AppController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(#[CurrentUser] User $currentUser): Response {
        // dd($currentUser->getAvailableProperty());

        return $this->render('/dashboard/index.html.twig', [
            'auth' => $currentUser->getProperty()
        ]);
    }

    #[Route('/contact', name: 'contact_agent')]
    public function contact(MessageBusInterface $bus): Response {
        // Serialization of 'class@anonymous' is not allowed
        // $notification = new class {
        //     public function getId(): int {
        //         return 37;
        //     }

        //     public function getAgent(): object {
        //         return new class {
        //             public function getEmail(): string {
        //                 return 'paolo_catalan@yahoo.com';
        //             }
        //         };
        //     }
        // };

        // $bus->dispatch(new ContactAgentNotification($notification->getId()));

        return $this->render('contact/index.html.twig');
    }



}
