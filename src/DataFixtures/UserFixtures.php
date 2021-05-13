<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Utils\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_ADMIN_1_EMAIL = 'admin_1@ranked-choice.com';
    public const USER_ADMIN_1_PASSWORD = 'admin123';

    public const USER_SUPER_ADMIN_1_EMAIL = 'super_admin_1@ranked-choice.com';
    public const USER_SUPER_ADMIN_1_PASSWORD = 'sadmin123';

    public const USER_1_EMAIL = 'test_user_1@gmail.com';
    public const USER_1_PASSWORD = 'user123';

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    private function getDefaultUsersData(): \Generator
    {
        yield [
            'email' => self::USER_ADMIN_1_EMAIL,
            'password' => self::USER_ADMIN_1_PASSWORD,
            'roles' => ['ROLE_ADMIN'],
        ];
        yield [
            'email' => self::USER_SUPER_ADMIN_1_EMAIL,
            'password' => self::USER_SUPER_ADMIN_1_PASSWORD,
            'roles' => ['ROLE_SUPER_ADMIN'],
        ];
        yield [
            'email' => self::USER_1_EMAIL,
            'password' => self::USER_1_PASSWORD,
            'roles' => ['ROLE_USER'],
        ];
    }

    public function load(ObjectManager $manager)
    {
        $userGenerator = $this->getDefaultUsersData();
        foreach ($userGenerator as $userRaw) {
            $user = new User();
            $user->setEmail($userRaw['email']);
            $user->setUsername($userRaw['email']);
            $user->setIsVerified(true);
            $user->setRoles($userRaw['roles']);

            $this->userManager->encodePassword($user, $userRaw['password']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
