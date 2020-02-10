<?php declare(strict_types = 1);

namespace App\EventSubscriber;

use Defuse\Crypto\Crypto;
use Gesdinet\JWTRefreshTokenBundle\Event\RefreshEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * A little hacked class to update common JWTLexik and Refresh bundles
 * to be able to permit Double Submit Cookie protection
 */
class JWTMiddlewareSubscriber implements EventSubscriberInterface
{
    private const SECURE_KEY_NAME   = 'SKEY';
    private const SECURE_ID_NAME    = 'SID';

    /** @var string|null */
    private $token = null;

    /** @var string|null */
    private $refreshToken = null;

    /** @var RequestStack */
    private $requestStack;

    /** @var string */
    private $secret;

    /** @var int */
    private $tokTtl;

    /** @var int */
    private $refreshTokTtl;

    /** @var bool */
    private $invalidateResponse = false;

    public function __construct(
        RequestStack $requestStack,
        string $secret,
        int $tokTtl = 300,
        int $refreshTokTtl = 2592000
    ) {
        $this->requestStack = $requestStack;
        $this->secret = $secret;
        $this->tokTtl = $tokTtl;
        $this->refreshTokTtl = $refreshTokTtl;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED         => 'onJWTCreated',
            Events::JWT_DECODED         => 'onJWTDecoded',
            KernelEvents::RESPONSE      => 'onResponse',
            'gesdinet.refresh_token'    => 'onRefreshToken',
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $this->token = md5((string)openssl_random_pseudo_bytes(8));

        $data = $event->getData();
        $data[self::SECURE_KEY_NAME] = Crypto::encryptWithPassword($this->token, $this->secret);

        $event->setData($data);
    }

    public function onResponse(FilterResponseEvent $event): void
    {
        if ($this->invalidateResponse) {
            $event->setResponse(new JsonResponse([
                'message' => 'Bad Request',
            ], Response::HTTP_BAD_REQUEST));

            return;
        }

        if ($this->refreshToken) {
            $event->getResponse()->headers->setCookie(
                $this->createRefreshCookie($this->refreshToken)
            );
        }

        if ($this->token) {
            $event->getResponse()->headers->setCookie(
                $this->createTokenCookie($this->token)
            );

            $refreshToken = json_decode($event->getResponse()->getContent())->refresh_token ?? '';

            if ($refreshToken) {
                $event->getResponse()->headers->setCookie(
                    $this->createRefreshCookie($refreshToken)
                );
            }
        }
    }

    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        if (!$this->requestStack->getCurrentRequest() instanceof Request) {
            return;
        }

        $payload = $event->getPayload();
        $request = $this->requestStack->getCurrentRequest();
        $key = $payload[self::SECURE_KEY_NAME] ?? '';
        $key = $key
            ? Crypto::decryptWithPassword($key, $this->secret)
            : $key;

        if (!$key || $key !== $request->cookies->get(self::SECURE_KEY_NAME)) {
            $event->markAsInvalid();
        }
    }

    public function onRefreshToken(RefreshEvent $event): void
    {
        if (!$this->requestStack->getCurrentRequest() instanceof Request) {
            return;
        }

        $idCookie = $this->requestStack->getCurrentRequest()->cookies->get(self::SECURE_ID_NAME);
        $idCookie = $idCookie
            ? Crypto::decryptWithPassword($idCookie, $this->secret)
            : $idCookie;

        if (!$this->token && $event->getRefreshToken()->getRefreshToken() !== $idCookie) {
            $this->invalidateResponse = true;

            return;
        }

        $this->refreshToken = $event->getRefreshToken()->getRefreshToken();
    }

    private function createRefreshCookie(string $val): Cookie
    {
        return new Cookie(
            self::SECURE_ID_NAME,
            Crypto::encryptWithPassword($val, $this->secret),
            time() + $this->refreshTokTtl
        );
    }

    private function createTokenCookie(string $val): Cookie
    {
        return new Cookie(self::SECURE_KEY_NAME, $val, time() + $this->tokTtl);
    }
}
