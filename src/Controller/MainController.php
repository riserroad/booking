<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event; 
use App\Entity\RegistrationEvent;
use App\Repository\EventRepository;
use App\Repository\RegistrationEventRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;

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

    /**
     * @Route("/registration_event/{event}", name="registration_event")
     */
    public function registrationEvent(Event $event,  RegistrationEventRepository $repoRegistrationEvent)
    {
        // cette route permet de realisé l'inscription. 

        // on vérifie si l'utilisateur est connecté 
        $user = $this->getUser(); 
        if ($user)
        {
            dump('user is conneted'); 
            // l'utilisateur est connecté, on récuperer les information. 

            // on crée l'objet registration event. 
            $registrationEvent = new RegistrationEvent; 
            // on stocke les data dans l'objet 
            $registrationEvent
                ->setEvent($event)
                ->setUser($user)
                ->setRegistrationDate(New \DateTime()); 

            // on vérifie si il a encore de la place pour event 
            $currentparticipantForEvent =  count($repoRegistrationEvent->findBy(['event' => $event])); 
            dump($currentparticipantForEvent); 
            if ( $event->getLimitParticipant() >= $currentparticipantForEvent)
            {
                // il reste de la place pour l'event, on confirme sa participation à l'event. 
                $registrationEvent->setIsConfirmed(true); 
            }

            // on enregistre inscription dans la BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($registrationEvent);
            $entityManager->flush(); 

            // on envoie un mail à l'utilisateur pour la confirmation d'inscription 
            // mailManager->sendComfirmation($registrationEvent)

            // on crére le message flash pour afficher sur la page suivante. 

            return $this->render('base.html.twig'); 
            
        }

        // utilisateur n'est pas connecté, on le dirige vers la page de login
        return $this->redirectToRoute('app_login'); 
        // temporaire, je veux rester sur la page lors du débug pour intercepter les dump 
        //return $this->render('base.html.twig'); 
    }

    /**
     * @route("/profile", name="profile")
     */
    public function profileDashboard()
    {
        $user = $this->getUser(); 
        if ($user)
        {
            // utilisateur est connecté, on affiche la page profile.
            return $this->render('main/profile.html.twig'); 
        }
        // l'utilsateur n'est pas connecté, on le redirige vers la page de login 
        return $this->redirectToRoute('app_login');
    }

}
