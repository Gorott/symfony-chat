<?php

namespace App\DataFixtures;

use App\Factory\ChatFactory;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct
    (protected UserRepository $userRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['username' => 'test']);
        ChatFactory::createOne(['users' => [$this->userRepository->findOneBy(['username' => 'test'])]]);
        $manager->flush();
    }
}
