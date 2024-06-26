<?php

namespace WebBook\ResponsiveImages\Classes;

use Closure;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use WebBook\ResponsiveImages\Models\Settings;
use Psr\Log\LoggerInterface;

/**
 * Class ResponsiveImagesMiddleware
 *
 * @package WebBook\ResponsiveImages\Classes
 */
class ResponsiveImagesMiddleware
{
    /**
     * Add srcset and sizes attributes to all local
     * images and create the various image sizes.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only handle default responses (no redirects)
        // Ignore non-html responses and backend responses
        if ( ! $this->isRelevant($request, $response)) {
            return $response;
        }

        /** @var LoggerInterface $logger */
        $logger = app('log');

        try {
            if ($this->isJsonResponse($response)) {
                return $this->handleJsonResponse($response, $logger);
            }

            $manipulator = new DomManipulator(
                $response->getContent(),
                $this->getSettings(),
                $logger
            );

            $response->setContent($manipulator->process());
        } catch (\Throwable $e) {
            $logger->warning(
                '[WebBook.ResponsiveImages] DOM manipulation failed: ' . $e->getMessage(),
                ['exception' => $e]
            );
        }


        return $response;

    }

    /**
     * Check if the requested path starts with
     * the configured backend uri.
     *
     * @param Request $request
     *
     * @return bool
     */
    private function isBackendRequest(Request $request)
    {
        return starts_with(
            trim($request->getPathInfo(), '/'),
            trim(Config::get('cms.backendUri', 'backend'), '/')
        );
    }

    /**
     * Check the content type of the response.
     *
     * @param Response $response
     *
     * @return bool
     */
    private function isHtmlResponse(Response $response)
    {
        return starts_with($response->headers->get('content-type'), 'text/html');
    }

    /**
     * Check the content type of the response.
     *
     * @param Response $response
     *
     * @return bool
     */
    private function isJsonResponse(Response $response)
    {
        return starts_with($response->headers->get('content-type'), 'application/json');
    }

    /**
     * @param Response $response
     * @param LoggerInterface $logger
     * @return Response
     * @throws \JsonException
     */
    private function handleJsonResponse(Response $response, LoggerInterface $logger): Response
    {
        $responseFields = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $responseFields = $this->handleJson($responseFields, $logger);

        $response->setContent(json_encode($responseFields, JSON_THROW_ON_ERROR));

        return $response;
    }

    /**
     * Recursively handle all fields in a json response.
     * @param array $data
     * @param $logger
     * @return array
     */
    public function handleJson(array &$data, $logger)
    {
        foreach ($data as $key => $value) {
            if (is_string($value) && str_contains($value, '<img')) {
                $manipulator = new DomManipulator(
                    $value,
                    $this->getSettings(),
                    $logger
                );
                $data[$key] = $manipulator->process();
                continue;
            }

            if (is_array($value)) {
                $data[$key] = $this->handleJson($value, $logger);
            }
        }

        return $data;
    }

    /**
     * Checks whether the response should be processed
     * by this middleware.
     *
     * @param $request
     * @param $response
     *
     * @return bool
     */
    protected function isRelevant($request, $response)
    {
        // Only default responses, no redirects
        if ( ! $response instanceof Response) {
            return false;
        }

        if ($this->isJsonResponse($response)) {
            return true;
        }

        if ( ! $this->isHtmlResponse($response)) {
            return false;
        }

        if ($this->isBackendRequest($request)) {
            return false;
        }

        if ($response->getContent() === '') {
            return false;
        }

        return true;
    }

    /**
     * Load all relevant Settings for the DomManipulator.
     *
     * @return DomManipulatorSettings
     */
    protected function getSettings(): DomManipulatorSettings
    {
        return DomManipulatorSettings::fromSettingsModel(Settings::instance());
    }

}
