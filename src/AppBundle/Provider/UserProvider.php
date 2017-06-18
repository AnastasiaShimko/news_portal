<?php

namespace AppBundle\Provider;


use AppBundle\Constants\RoleConstants;
use AppBundle\Entity\BasicUser;
use AppBundle\Entity\ChangedUser;
use AppBundle\Entity\RegisteredUser;
use AppBundle\Entity\User;
use AppBundle\Entity\UserParameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProvider
{
    private $entityManager;
    private $passwordEncoder;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->repository = $entityManager->getRepository('AppBundle:User');
    }

    public function createUser(User $user){
        $user->setRole('ROLE_NOT_CONFIRMED');
        $parameters = new UserParameters();
        $user->setParameters($parameters);
        $this->codePassword($user, $user->getPassword());
        $this->entityManager->persist($parameters);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getUser(string $email){
        return  $this->repository->findOneBy(array('email' => $email));
    }

    public function getAllUsersByRole(string $role){
        return $this->repository->findBy(array('role' => RoleConstants::$NUMBER_FROM_ROLES[$role]));
    }

    public function checkPasswordChangeUser(ChangedUser $changedUser, User $user){
        if($this->passwordEncoder->isPasswordValid($user, $changedUser->getOldPassword())){
            $user->setNotification($changedUser->getNotification());
            if($changedUser->getPassword()) {
                $this->codePassword($user, $changedUser->getPassword());
            }
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function changeRole(User $user, string $role){
        if (in_array($role,  RoleConstants::$ROLES_FROM_NUMBER) && $user->getRole()!= $role){
            $user->setRole($role);
            $this->entityManager->flush();
        }
    }

    public function codePassword(User $user, $password)
    {
        $password = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);
    }
}