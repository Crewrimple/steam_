<?php

namespace SteamApi\Requests;

use Carbon\Carbon;
use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;

class ItemOrdersHistogram extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/market/listings/%s/%s";
    const URL = "https://steamcommunity.com/market/itemordershistogram?country=%s&language=%s&currency=%s&item_nameid=%s&two_factor=%s";

    private $method = 'GET';

    private $appId;
    private $marketHashName = '';

    private $country = 'US';
    private $language = 'english';
    private $currency = 1;
    private $itemNameId = null;
    private $twoFactor = 0;

    /**
     * @param $appId
     * @param array $options
     * @throws InvalidOptionsException
     */
    public function __construct($appId, array $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->country, $this->language, $this->currency, $this->itemNameId, $this->twoFactor);
    }

    public function getHeaders()
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'If-Modified-Since' => Carbon::now('UTC')->subSeconds(10)->toRfc7231String(),
            'Referer' => sprintf(self::REFERER, $this->appId, $this->marketHashName)
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

        if (isset($options['item_name_id']))
            $this->itemNameId = $options['item_name_id'];
        else
            throw new InvalidOptionsException("Option 'item_name_id' must be filled");

        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
        $this->twoFactor = isset($options['two_factor']) ? $options['two_factor'] : $this->twoFactor;
    }
}