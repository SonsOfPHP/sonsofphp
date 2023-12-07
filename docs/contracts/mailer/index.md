---
title: Mailer Contract
---

## Installation

```shell
composer require sonsofphp/mailer-contract
```

## Definitions

- **Message** - The email message that will be sent.
- **Mailer** - Primary API used to send *messages*.
- **Transport** - Transports are what will actually send the message. This could
  be SMTP, Null, SendGrid, Amazon SES, or anything like that.
- **Middleware** - Before the message is sent to the transport to be sent,
  various middlewares have the ability to modify the message.
