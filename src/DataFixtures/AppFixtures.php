<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface  $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setEmail("user" . $i . "@gmail.com");
            $user->setNickName("user" . $i);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getNickName()));
            $manager->persist($user);
        }



        $manager->flush();
    }
}
