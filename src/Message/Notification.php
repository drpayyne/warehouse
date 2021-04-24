<?php declare(strict_types=1);

namespace App\Message;

/**
 * Class to hold the notification content.
 *
 * @author Adarsh Manickam <adarsh.apple@icloud.com>
 */
class Notification
{
    /**
     * @var string
     */
    private string $content;

    /**
     * Notification constructor.
     *
     * @param string $content
     */
    public function __construct(
        string $content
    ) {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}