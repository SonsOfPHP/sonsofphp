---
title: Event Messages
---

# Event Messages

Event Messages are what Aggregates will raise and what the Event Message
Handlers will handle. Event Messages will also be stored in whatever storage
backend you decide to use. They are used to rebuild the Aggregates as well. So
as you can see, Event Messages are a big part of Event Sourcing!

Event Messages consist of 1) Metadata and 2) Payload. The Metadata contains
information about the event and the aggregate. The payload is the data that is
passed in and used to rebuild the aggregates.
