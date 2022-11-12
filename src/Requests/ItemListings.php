<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;

class ItemListings extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/market/listings/%s/%s";
    const URL = "https://steamcommunity.com/market/listings/%s/%s/render/?query=%s&start=%s&count=%s&country=%s&language=%s&currency=%s&filter=%s";

    private $method = 'GET';

    private $appId;
    private $marketHashName = '';

    private $query = '';
    private $start = 0;
    private $count = 10;
    private $currency = 1;
    private $country = 'US';
    private $language = 'english';
    private $filter = '';

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
        return sprintf(self::URL, $this->appId, $this->marketHashName, $this->query, $this->start, $this->count,
            $this->country, $this->language, $this->currency, $this->filter);
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => sprintf(self::REFERER, $this->appId, $this->marketHashName) . ($this->filter ? '?filter=' . $this->filter : '')
        ];
    }

    /**
     * @param array $proxy
     * @param false $detailed
     * @param false $multiRequest
     * @param array $curlOpts
     * @return mixed|void
     * @throws InvalidClassException
     */
    public function call(array $proxy = [], bool $detailed = false, bool $multiRequest = false, array $curlOpts = [])
    {
        return $this->makeRequest($proxy, $detailed, $multiRequest, $curlOpts);
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

        $this->query = isset($options['query']) ? $options['query'] : $this->query;
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->filter = isset($options['filter']) ? $options['filter'] : $this->filter;
    }
}