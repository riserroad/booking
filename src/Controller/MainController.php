<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event; 
use App\Repository\EventRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        // recupere la liste des event 
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll(); 

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'events' => $events, 
        ]);
    }
    /**
     * @Route("/event/{event}", name="event")
     */
    public function event(Event $event)
    {
        return $this->render('main/event.html.twig', [
            'event' => $event,
        ]); 
    }
}
