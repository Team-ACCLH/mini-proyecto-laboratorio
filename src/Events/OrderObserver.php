<?php

/** Contrato de quien quiere enterarse de que se creo un pedido. */
interface OrderObserver
{
    public function update(Order $order): void;
}
