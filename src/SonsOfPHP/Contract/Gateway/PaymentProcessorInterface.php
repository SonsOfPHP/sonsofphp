<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Gateway;

/**
 * Processors are basically merchant accounts. They will take credit cards or
 * other payment types and charge a customer.
 *
 * Processor Examples
 *   - Stripe
 *   - Authorize.net
 *   - Dummy or Mock
 *   - Round Robin
 *   - Capped
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface PaymentProcessorInterface
{
    /**
     * MUST return the friendly name such as "Stripe"
     *
     * This MAY BE used as a display on the frontend
     */
    public function getName(): string;

    /**
     * Request is sent to the payment processor and a response is returned
     */
    public function request(RequestInterface $request): ResponseInterface;
}
