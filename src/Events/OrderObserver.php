<?php

namespace Src\Events;

interface OrderObserver
{
    public function update(array $orderData): void;
}
