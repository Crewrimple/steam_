<?php

namespace SteamApi\Requests\Steam;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;

class ItemNameId extends Request implements RequestInterface
{
    const REFERER = 'https://steamcommunity.com/market/listings/%s/%s';
    const URL = 'https://steamcommunity.com/market/listings/%s/%s';

    private $method = 'GET';

    private $appId;
    private $marketHashName = '';

    /**
     * @throws InvalidOptionsException
     */
    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->appId, $this->marketHashName);
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => sprintf(self::REFERER, $this->appId, $this->marketHashName)
        ];
    }

    /**
     * @param array $proxy
     * @param false $detailed
     * @param false $multiRequest
     * @param string $cookies
     * @param array $curlOpts
     * @return mixed|void
     * @throws InvalidClassException
     */
    public function call(array $proxy = [], string $cookies = '', bool $detailed = false, array $curlOpts = [], bool $multiRequest = false)
    {
        return $this->makeRequest($proxy, $cookies, $detailed, $curlOpts, $multiRequest);
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->method;
    }

    /**
     * @param array $options
     * @throws InvalidOptionsException
     */
    private function setOptions(array $options)
    {
        if (isset($options['market_hash_name']))
            $this->marketHashName = rawurlencode($options['market_hash_name']);
        else
            throw new InvalidOptionsException("Option 'market_hash_name' must be filled");
    }
}