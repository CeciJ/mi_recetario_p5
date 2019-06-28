<?php

namespace App\Notification;

use App\Entity\ContactMail;
use Twig\Environment;

class ContactMailNotification
{

    private $mailer;

    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(ContactMail $contactMail)
    {
        $message = (new \Swift_Message('Agence : '.$contactMail->getRecipe()->getName()))
            ->setFrom($contactMail->getEmail())
            ->setTo('cec.jourdan@gmail.com')
            ->setReplyTo($contactMail->getEmail())
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contactMail
            ]), 'text/html');
        $this->mailer->send($message);

    }
}