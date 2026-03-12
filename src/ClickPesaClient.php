<?php

namespace FeedTan\ClickPesa;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClickPesaClient
{
    protected Client $client;
    protected array $config;
    protected ?string $token = null;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->getApiUrl(),
            'timeout' => $config['timeout']['request'] ?? 60,
            'connect_timeout' => $config['timeout']['connect'] ?? 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Get API URL based on environment.
     */
    protected function getApiUrl(): string
    {
        return $this->config['sandbox'] ?? false 
            ? 'https://sandbox-api.clickpesa.com/v1'
            : ($this->config['api_url'] ?? 'https://api.clickpesa.com/v1');
    }

    /**
     * Get authentication token.
     */
    public function getToken(): string
    {
        if ($this->token) {
            return $this->token;
        }

        $cacheKey = $this->config['token']['cache_key'] ?? 'clickpesa_token';
        $cacheTtl = $this->config['token']['cache_ttl'] ?? 3600;

        $this->token = Cache::remember($cacheKey, $cacheTtl, function () {
            return $this->requestToken();
        });

        return $this->token;
    }

    /**
     * Request new authentication token from ClickPesa API.
     */
    protected function requestToken(): string
    {
        try {
            $response = $this->client->post('/auth/token', [
                'json' => [
                    'api_key' => $this->config['api_key'],
                    'api_secret' => $this->config['api_secret'],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['access_token'])) {
                throw new \Exception('Failed to obtain access token');
            }

            $this->logInfo('Token obtained successfully', ['token_length' => strlen($data['access_token'])]);

            return $data['access_token'];
        } catch (RequestException $e) {
            $this->logError('Token request failed', [
                'status' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            throw new \Exception('Failed to authenticate with ClickPesa API: ' . $e->getMessage());
        }
    }

    /**
     * Make authenticated API request.
     */
    protected function request(string $method, string $endpoint, array $data = [], array $options = []): array
    {
        try {
            $token = $this->getToken();
            
            $requestOptions = array_merge([
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ], $options);

            if (!empty($data)) {
                $requestOptions['json'] = $data;
            }

            $this->logInfo("API Request: {$method} {$endpoint}", ['data' => $data]);

            $response = $this->client->request($method, $endpoint, $requestOptions);
            $responseData = json_decode($response->getBody()->getContents(), true);

            $this->logInfo("API Response: {$method} {$endpoint}", [
                'status' => $response->getStatusCode(),
                'data' => $responseData,
            ]);

            return $responseData;
        } catch (RequestException $e) {
            $this->logError("API Error: {$method} {$endpoint}", [
                'status' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
                'data' => $data,
            ]);

            throw new \Exception('ClickPesa API error: ' . $e->getMessage());
        }
    }

    /**
     * GET request.
     */
    public function get(string $endpoint, array $params = []): array
    {
        if (!empty($params)) {
            $endpoint .= '?' . http_build_query($params);
        }
        return $this->request('GET', $endpoint);
    }

    /**
     * POST request.
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, $data);
    }

    /**
     * PUT request.
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, $data);
    }

    /**
     * DELETE request.
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Log info message.
     */
    protected function logInfo(string $message, array $context = []): void
    {
        if ($this->config['logging']['enabled'] ?? true) {
            Log::channel($this->config['logging']['channel'] ?? 'clickpesa')
                ->info($message, $context);
        }
    }

    /**
     * Log error message.
     */
    protected function logError(string $message, array $context = []): void
    {
        if ($this->config['logging']['enabled'] ?? true) {
            Log::channel($this->config['logging']['channel'] ?? 'clickpesa')
                ->error($message, $context);
        }
    }
}
