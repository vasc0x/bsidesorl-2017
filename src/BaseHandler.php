<?php
declare(strict_types=1);
namespace ParagonIE\BsidesOrl2017Talk;

use GuzzleHttp\Psr7\{
    Response,
    ServerRequest
};
use Psr\Log\LoggerInterface;

/**
 * Class BaseHandler
 * @package ParagonIE\BsidesOrl2017Talk
 */
class BaseHandler
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ServerRequest
     */
    protected $request;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public function __construct(ServerRequest $request, LoggerInterface $logger)
    {
        $this->request = $request;
        $this->logger = $logger;

        $templates = \dirname(__DIR__) . '/templates';

        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem([$templates]),
            [
                'autoescape' => true
            ]
        );
    }

    /**
     * @param string $contentType
     * @return array
     */
    public function getHeaders(string $contentType): array
    {
        return [
            'Content-Type' => $contentType,
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block'
        ];
    }

    /**
     * @param string $template
     * @param array $options
     * @param string $mimeType
     * @return Response
     */
    public function render(string $template = 'base.twig', array $options = [], string $mimeType = 'text/html'): Response
    {
        $body = $this->twig->render($template, $options);
        return new Response(200, $this->getHeaders($mimeType), $body);
    }
}
