<?php

namespace App\Messenger\Message\Command;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class SendUserNewPassword
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $plainPassword;

    public function __construct(string $userId, string $plainPassword)
    {
        $this->userId = $userId;
        $this->plainPassword = $plainPassword;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
