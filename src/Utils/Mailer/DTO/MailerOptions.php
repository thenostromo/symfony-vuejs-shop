<?php

namespace App\Utils\Mailer\DTO;

use App\Entity\Order;
use App\Entity\User;

class MailerOptions
{
    /**
     * @var string
     */
    public $recipient;

    /**
     * @var string
     */
    public $cc;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $htmlTemplate;

    /**
     * @var array
     */
    public $context;

    /**
     * @var string
     */
    public $text;
}
