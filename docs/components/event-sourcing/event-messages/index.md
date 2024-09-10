---
title: Message Serializers
---

# Message Serializers

Message Serializers will `serialize` an Event Message into Event Data and
`deserialize` Event Data into an Event Message. These are generally used by the
Message Repository classes and `serialize` is done before the event message is
persisted to storage and `deserialize` is done on the data that is pulled out of
the database.
