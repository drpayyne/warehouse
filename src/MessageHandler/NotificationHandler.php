<?php declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\Notification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class to handle notifications.
 *
 * @author Adarsh Manickam <adarsh.apple@icloud.com>
 */
class NotificationHandler implements MessageHandlerInterface
{
    public function __invoke(Notification $notification) {}
}