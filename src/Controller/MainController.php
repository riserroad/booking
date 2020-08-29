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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $user = $this->getUser(); 
        return $this->render('main/event.html.twig', [
            'event' => $event,
            'user' => $user,
        ]); 
    }

    /**
     * @Route("/registration_event/{event}", name="registration_event")
     */
    public function registrationEvent(Event $event,  RegistrationEventRepository $repoRegistrationEvent, ValidatorInterface $validator )
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

            // on vérifie les donnée 
            $errors = $validator->validate($registrationEvent); 

            dump($errors); 

            if (count($errors) == 0)
            {
                // on enregistre inscription dans la BDD
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($registrationEvent);
                $entityManager->flush(); 

                // message flash 
                $this->addFlash('notice', 'Tu es inscrit à l\'event');

                // on envoie un mail à l'utilisateur pour la confirmation d'inscription 
                // mailManager->sendComfirmation($registrationEvent)

            }
            else
            {
                $this->addFlash('error', $errors); 
            }
            
            // changé en redirect to route ( risque de partage du lien qui fais mal ) 
            return $this->redirectToRoute('event', ['event' => $event->getId()]); 
            
        }

        // utilisateur n'est pas connecté, on le dirige vers la page de login
        return $this->redirectToRoute('app_login'); 
        // temporaire, je veux rester sur la page lors du débug pour intercepter les dump 
        //return $this->render('base.html.twig'); 
    }

    /**
     * @route("/cancel_registration_event/{event}" ,  name="cancel_registration_event")
     */
    public function cancelRegistrationEvent(Event $event, RegistrationEventRepository $repoRegistrationEvent)
    {
        $user = $this->getUser();
        if ($user)
        {
            // l'utilisateur est connecté 

            // on recupere l'enregistrement en recherchant avec user et id de l'event 
            $registrationEvent = $repoRegistrationEvent->findOneBy(['event' => $event, 'user' => $user]);
            // on vérifie si l'enregistrement est trouvé 
            if ($registrationEvent)
            {
                // enregistrement existe, on peut supprimer de la BDD
                $entityManager = $this->getDoctrine()->getManager(); 
                $entityManager->remove($registrationEvent); 

                $entityManager->flush();

                $this->addFlash('notice', 'Annulation effectué'); 
                
            }
            else
            {
                // enregistrement n'exite pas, on lance une exception

                $this->addFlash('error', 'pas de registration'); 

                // on lance la vérification de la liste d'attente

            }
        }
        else 
        {
            $this->addFlash('error', 'utilisateur non connecté'); 
        }

        return $this->redirectToRoute('event', ['event' => $event->getId()]); 
    }

    /**
     * @route("/profile", name="profile")
     */
    public function profileDashboard()
    {
        $user = $this->getUser(); 
        if ($user)
        {
            $hashUserGravatar = md5($user->getEmail());
            dump($hashUserGravatar); 
            // utilisateur est connecté, on affiche la page profile.
            return $this->render('main/profile.html.twig', [
                'hashusergravatar' => $hashUserGravatar, 
            ]); 
        }
        // l'utilsateur n'est pas connecté, on le redirige vers la page de login 
        return $this->redirectToRoute('app_login');
    }

}
