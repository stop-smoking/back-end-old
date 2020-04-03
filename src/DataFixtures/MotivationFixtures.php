<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Motivation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MotivationFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $motivation = new Motivation();
            $motivation->setTitle('Motivation-' . $i);
            $motivation->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
            $motivation->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
            $motivation->setText('string');
            $motivation->setImage('string');
            $manager->persist($motivation);
        }

        $manager->flush();
    }
}
