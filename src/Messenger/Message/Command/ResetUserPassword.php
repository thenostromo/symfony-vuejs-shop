<?php

namespace App\Messenger\Message\Command;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class ResetUserPassword
{
    /**
     * @var string
     */
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
