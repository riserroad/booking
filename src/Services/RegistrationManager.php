<?php 

namespace App\Services;

use App\Entity\Event;
use App\Entity\RegistrationEvent;
use App\Entity\User;
use App\Repository\RegistrationEventRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationManager
{
    private $repoRegistrationEvent;
    private $validator;
    private $em; 

    public function __construct(ValidatorInterface $validator, EntityManager $em, RegistrationEventRepository $repoRegistrationEvent)
    {
        $this->validator = $validator;
        $this->em = $em; 
        $this->repoRegistrationEvent = $repoRegistrationEvent;
    }

    public function addRegistration(User $user, Event $event)
    {
        // enregistrement d'un inscrit 


        // on crée objet registration event 
        $registrationEvent = new RegistrationEvent;
        // on stocke les valeur dans l'objet 
        $registrationEvent
            ->setEvent($event)
            ->setUser($user)
            ->setRegistrationDate(new \DateTime());

        // on vérifie si il reste de la place 
        $numberParticipantCurrent = count($event->getRegistrationEvents());
        if ( $event->getLimitParticipant() > $numberParticipantCurrent )
        {
            // il reste de la place 
            $registrationEvent->setIsConfirmed(true); 
        }

        // on vérifie les data 
        $errors = $this->validator->validate($registrationEvent); 

        if (count($errors) == 0 )
        {
            // les data sont correct, on enregistre les data dans la BDD
            $this->em->persist($registrationEvent);
            $this->em->flush();

            return false; 
        }
        else 
        {
            return $errors; 
        }


    }

    public function removeRegistration(User $user, Event $event)
    {
        $registrationEvent = $this->repoRegistrationEvent->findBy(['event' => $event, 'user' => $user]);
        if($registrationEvent)
        {
            $this->em->remove($registrationEvent);
            $this->em->flush();

            return true;
        }
        else 
        {
            return false; 
        }
    }
}