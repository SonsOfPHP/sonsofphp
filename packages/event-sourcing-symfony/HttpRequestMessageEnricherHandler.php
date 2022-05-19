<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Http Request Enricher.
 *
 * Adds HTTP Request information to the event message's metadata
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class HttpRequestMessageEnricherHandler implements MessageEnricherHandlerInterface
{
    public const METADATA_HTTP_REQUEST = '__http_request';

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function enrich(MessageInterface $message): MessageInterface
    {
        $request = $this->requestStack->getMainRequest();

        if (null !== $request) {
            return $message->withMetadata([
                self::METADATA_HTTP_REQUEST => [
                    'http_user_agent' => $request->server->get('HTTP_USER_AGENT'),
                    'client_ip' => $request->getClientIp(),
                    'content_type' => $request->getContentType(),
                    'method' => $request->getMethod(),
                    'host' => $request->getHost(),
                    'path_info' => $request->getPathInfo(),
                    'query_string' => $request->getQueryString(),
                    'locale' => $request->getLocale(),
                    'is_xml_http_request' => $request->isXmlHttpRequest(),
                ],
            ]);
        }

        return $message;
    }
}
