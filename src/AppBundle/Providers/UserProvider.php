<?php

namespace AppBundle\Providers;


use AppBundle\Constants\RoleConstants;
use AppBundle\Entity\RegisteredUser;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProvider
{

    public function createUser(RegisteredUser $registeredUser, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $this->mergeUser($registeredUser, $user, $passwordEncoder);
        $em->persist($user);
        $em->flush();
        return $user;
    }

    private function mergeUser(RegisteredUser $registeredUser,  User $user, UserPasswordEncoderInterface $passwordEncoder){
        $password = $passwordEncoder->encodePassword($user, $registeredUser->getPassword());
        $user->setPassword($password);
        $user->setEmail($registeredUser->getEmail());
        $user->setNotification($registeredUser->getEmail());
    }

    public function changePassword(EntityManagerInterface $em, User $user, string $password,
                                    UserPasswordEncoderInterface $passwordEncoder){
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $em->flush();
    }

    public function getUser(EntityManagerInterface $em, string $email){
        $repository = $em->getRepository('AppBundle:User');
        return  $repository->findOneBy(array('email' => $email));
    }


    public function getAllUsersByRole(EntityManagerInterface $em, string $role){
        $repository = $em->getRepository('AppBundle:User');
        return $repository->findBy(array('role' => RoleConstants::$NUMBER_FROM_ROLES[$role]));
    }


    public function changeRole(User $user, string $role, EntityManagerInterface $em){
        if (in_array($role,  RoleConstants::$ROLES_FROM_NUMBER) && $user->getRole()!= $role){
            $user->setRole($role);
            $em->flush();
        }
    }
}