<?php

namespace App\MessageHandler;

use App\Message\RapportNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RapportNotificationHandler
{
    public function __invoke(RapportNotification $message)
    {
        file_put_contents("./rapport.json", $message->getContent());
    }

}