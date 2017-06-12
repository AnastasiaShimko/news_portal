<?php

namespace AppBundle\Provider;


use AppBundle\Constants\RoleConstants;
use AppBundle\Entity\RegisteredUser;
use AppBundle\Entity\User;
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

    public function createUser(RegisteredUser $registeredUser){
        $user = new User();
        $this->mergeUser($registeredUser, $user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    private function mergeUser(RegisteredUser $registeredUser,  User $user){
        $password = $this->passwordEncoder->encodePassword($user, $registeredUser->getPassword());
        $user->setPassword($password);
        $user->setEmail($registeredUser->getEmail());
        $user->setNotification($registeredUser->getEmail());
    }

    public function changePassword(User $user, string $password){
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $this->entityManager->flush();
    }

    public function getUser(string $email){
        return  $this->repository->findOneBy(array('email' => $email));
    }


    public function getAllUsersByRole(string $role){
        return $this->repository->findBy(array('role' => RoleConstants::$NUMBER_FROM_ROLES[$role]));
    }


    public function changeRole(User $user, string $role){
        if (in_array($role,  RoleConstants::$ROLES_FROM_NUMBER) && $user->getRole()!= $role){
            $user->setRole($role);
            $this->entityManager->flush();
        }
    }
}