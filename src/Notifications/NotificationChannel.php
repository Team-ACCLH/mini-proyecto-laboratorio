<?php

namespace Src\Notifications;

interface NotificationChannel
{
    public function send(string $message, string $recipient): void;
}
