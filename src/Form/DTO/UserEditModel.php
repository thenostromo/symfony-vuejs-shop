<?php

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserEditModel
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $plainPassword;

    /**
     * @var string
     */
    public $fullName;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $zipCode;

    /**
     * @var array
     */
    public $roles;

    /**
     * @var bool
     */
    public $isVerified;

    public static function makeFromUser(?User $user): self
    {
        $model = new self();
        if (!$user) {
            return $model;
        }

        $model->id = $user->getId();
        $model->fullName = $user->getFullName();
        $model->phone = $user->getPhone();
        $model->address = $user->getAddress();
        $model->zipCode = $user->getZipcode();
        $model->roles = $user->getRoles();
        $model->isVerified = $user->isVerified();

        return $model;
    }
}
