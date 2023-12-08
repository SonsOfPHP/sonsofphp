---
title: Gateway Contract
---

Gateway is to help handle payment processing. With so many different payment
processors our there, there should be a standard interface that developers can
use to be able to access all of them.

## Installation

```shell
composer require sonsofphp/pager-contract
```

## Scope

In Scope:
- Credit Cards, eCheck, and Tokens as Payment Methods
- authorize, capture, void, refund
  - Supports redirect response

Out of Scope
- Recurring Billing (Might be another component)
- Webhooks?
- Usage Based Billing?

## Meta

```php
<?php

$processor = new PaymentProcessor(key: '...', secret: '...');
$gateway = new Gateway($processor);

$card = new CreditCard();
$card->setNumber(...)->setCvv(...);

$transaction = $gateway->authorize(['card' => $card]);
$transaction = $gateway->capture(['card' => $card]);

// Token Based processors
$transaction = $gateway->purchase($token);

// Void and Refund
$transaction = $gateway->void(...);
$transaction = $gateway->refund(...);
```

Special Processor Examples
- Round Robin Processor
- Capped Processor
