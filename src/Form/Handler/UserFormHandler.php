<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Form\DTO\UserEditModel;
use App\Utils\Manager\UserManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFormHandler
{
    /**
     * @var UserManager
     */
    public $userManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserManager $userManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param UserEditModel $userEditModel
     * @return User
     */
    public function processUserEditForm(UserEditModel $userEditModel): User
    {
        $user = new User();

        if ($userEditModel->id) {
            $user = $this->userManager->find($userEditModel->id);
        }

        $user->setFullName($userEditModel->fullName);
        $user->setPhone($userEditModel->phone);
        $user->setAddress($userEditModel->address);
        $user->setZipcode($userEditModel->zipCode);
        $user->setIsVerified($userEditModel->isVerified);

        if ($userEditModel->plainPassword) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $userEditModel->plainPassword)
            );
        }

        $this->userManager->save($user);

        return $user;
    }
}
