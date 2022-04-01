<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


class MailjetService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param string $subject
     * @param string $message
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $sender, string $receiver, string $subject, string $message): void
    {
        $email = (new Email())
            ->from($sender)
            ->to($receiver)
            ->subject($subject)
            ->text($message);

        $this->mailer->send($email);

    }
}