<?php

namespace App\DataFixtures;

use App\Entity\Publicacion;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface  $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $filesystem = new Filesystem();

        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setEmail("user" . $i . "@gmail.com");
            $user->setNickName("user" . $i);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getNickName()));

            if (!$filesystem->exists('/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com')) {
                $filesystem->mkdir('/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com', 0777);
            }
            $manager->persist($user);

            if ($i < 3) {
                $publicacion = new Publicacion();
                $publicacion->setNombrePublicacion("Titulo " . $i);
                $publicacion->setDescripcion("Descripción " . $i);
                $publicacion->setUsuario($user);

                if (!$filesystem->exists('/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba')) {
                    $filesystem->mkdir('/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba', 0777);
                }

                if ($i == 1) {
                    rename('/home/dwes/Pictures/telefono1.jpg', '/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba/prueba1.jpg');
                    rename('/home/dwes/Pictures/telefono2.jpg', '/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba/prueba2.jpg');
                } else {
                    rename('/home/dwes/Pictures/pelota1.jpg', '/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba/prueba1.jpg');
                    rename('/home/dwes/Pictures/pelota2.jpg', '/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba/prueba2.jpg');
                }

                $publicacion->setImagenes([
                    "imagen1" => '/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba/prueba1.jpg',
                    "imagen2" => '/home/dwes/proyectoFinal/public/img/user' . $i . '@gmail.com/prueba/prueba2.jpg',
                ]);

                $manager->persist($publicacion);
            }
        }



        $manager->flush();
    }
}
