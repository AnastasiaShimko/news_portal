<?php

namespace AppBundle\Provider;


use AppBundle\Constants\RoleConstants;
use AppBundle\Entity\BasicUser;
use AppBundle\Entity\ChangedUser;
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

    private function mergeUser(BasicUser $basicUser, User $user)
    {
        if ($basicUser->getPassword()) {
            $password = $this->passwordEncoder->encodePassword($user, $basicUser->getPassword());
            $user->setPassword($password);
        }
        if ($basicUser->getEmail()) {
            $user->setEmail($basicUser->getEmail());
        }
        $user->setNotification($basicUser->getNotification());
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

    public function changeUser(ChangedUser $changedUser, User $user){
        if($this->passwordEncoder->isPasswordValid($user, $changedUser->getOldPassword())){
            $this->mergeUser($changedUser, $user);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function enc($user, $password){
        return $this->passwordEncoder->encodePassword($user, $password);
    }

    public function changeRole(User $user, string $role){
        if (in_array($role,  RoleConstants::$ROLES_FROM_NUMBER) && $user->getRole()!= $role){
            $user->setRole($role);
            $this->entityManager->flush();
        }
    }
}