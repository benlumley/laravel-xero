<?php

namespace Dcblogdev\Xero\Resources;

use Dcblogdev\Xero\Xero;

class Webhooks extends Xero
{
    protected string $payload;

    public function __construct(string $payload = null)
    {
        $this->payload = $payload;
    }

    public function validate(string $signature) : bool
    {
        return hash_equals($this->getSignature(), $signature);
    }

    public function getSignature(): string
    {
        return base64_encode(hash_hmac('sha256', $this->payload, config('xero.webhookKey'), true));
    }


    public function getEvents(string $signature) : array
    {
        $this->validate($signature);

        $payload = json_decode($this->payload);

        return $payload->events;
    }
}
