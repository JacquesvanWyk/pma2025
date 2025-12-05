<?php

declare(strict_types=1);

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

class UseSendTransport extends AbstractTransport
{
    public function __construct(
        private string $apiKey,
        private string $baseUrl = 'https://app.usesend.com/api/',
        ?EventDispatcherInterface $dispatcher = null,
        ?LoggerInterface $logger = null
    ) {
        parent::__construct($dispatcher, $logger);
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $payload = [
            'to' => $this->formatAddresses($email->getTo()),
            'from' => $this->formatAddress($email->getFrom()[0] ?? null),
            'subject' => $email->getSubject(),
        ];

        if ($email->getHtmlBody()) {
            $payload['html'] = $email->getHtmlBody();
        }

        if ($email->getTextBody()) {
            $payload['text'] = $email->getTextBody();
        }

        if ($email->getCc()) {
            $payload['cc'] = $this->formatAddresses($email->getCc());
        }

        if ($email->getBcc()) {
            $payload['bcc'] = $this->formatAddresses($email->getBcc());
        }

        if ($email->getReplyTo()) {
            $payload['replyTo'] = $this->formatAddress($email->getReplyTo()[0] ?? null);
        }

        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->post($this->baseUrl.'v1/emails', $payload);

        if ($response->failed()) {
            throw new \RuntimeException(
                'UseSend API error: '.$response->body()
            );
        }
    }

    private function formatAddresses(array $addresses): string
    {
        return collect($addresses)
            ->map(fn (Address $address) => $this->formatAddress($address))
            ->implode(', ');
    }

    private function formatAddress(?Address $address): string
    {
        if (! $address) {
            return '';
        }

        if ($address->getName()) {
            return sprintf('%s <%s>', $address->getName(), $address->getAddress());
        }

        return $address->getAddress();
    }

    public function __toString(): string
    {
        return 'usesend';
    }
}
