<?php declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\Notification;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class to handle notifications.
 *
 * @author Adarsh Manickam <adarsh.apple@icloud.com>
 */
class NotificationHandler implements MessageHandlerInterface
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * NotificationHandler constructor.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }

    /**
     * Sends an email with the received notification content.
     *
     * @param Notification $notification
     *
     * @throws TransportExceptionInterface
     */
    public function __invoke(Notification $notification) {
        $email = (new Email())
            ->from("warehouse@studioforty9.com")
            ->to("owner@studioforty9.com")
            ->subject("Out of stock alert!")
            ->text($notification->getContent());

        $this->mailer->send($email);
    }
}