<?php

namespace App\Lib;

use App\Models\ApiConfiguration;
use Illuminate\Validation\ValidationException;

class Reloadly
{
    private $baseURL         = 'https://topups.reloadly.com';
    private $sandboxURL      = 'https://topups-sandbox.reloadly.com';
    private $baseAudience    = "https://topups.reloadly.com";
    private $sandboxAudience = "https://topups-sandbox.reloadly.com";
    public  $useLocalAmount  = false;
    private $accessToken;
    private $accessTokenType;
    public  $operatorId;
    private $url;
    private $audience;

    public function __construct()
    {
        if (!$this->accessToken) {
            $this->setAccessToken();
        }
    }

    private function setAccessToken()
    {
        $apiConfig = ApiConfiguration::where('provider', 'reloadly')->first();

        if (!$apiConfig) {
            throw ValidationException::withMessages(['error' => 'Something went wrong! Try again later']);
        }

        $this->url      = $apiConfig->test_mode ? $this->sandboxURL : $this->baseURL;
        $this->audience = $apiConfig->test_mode ? $this->sandboxAudience : $this->baseAudience;

        $credentials = $apiConfig->credentials;
        $this->accessToken = $apiConfig->access_token;

        if (!$apiConfig->access_token ||  @$apiConfig->token_expired_on < now()) {
            $accessURL   = 'https://auth.reloadly.com/oauth/token';
            $headers     = ['Content-Type:application/json'];

            $data = [
                'client_id'     => @$credentials->client_id,
                'client_secret' => @$credentials->client_secret,
                'grant_type'    => 'client_credentials',
                'audience'      => $this->audience
            ];

            $data              = json_encode($data);

            $response = CurlRequest::curlPostContent($accessURL, $data, $headers);
            $response = json_decode($response);

            if (!@$response->access_token) {
                throw ValidationException::withMessages(['error' => 'Something went wrong! Try again later']);
            }

            $apiConfig->token_type       = $response->token_type;
            $apiConfig->access_token     = $response->access_token;
            $apiConfig->token_expired_on = now()->addSeconds($response->expires_in);
            $apiConfig->save();
        }

        $this->accessToken     = $apiConfig->access_token;
        $this->accessTokenType = $apiConfig->token_type;
    }

    public function getCountries()
    {
        $url      = $this->url . '/countries';
        $response = CurlRequest::curlContent($url, $this->getHeaders());
        return json_decode($response);
    }

    public function getOperators()
    {
        $url = $this->url . '/operators';
        $response = CurlRequest::curlContent($url, $this->getHeaders());
        return json_decode($response);
    }

    public function getOperatorsByISO($iso)
    {
        $url = $this->url . '/operators/countries/' . $iso;
        $response = CurlRequest::curlContent($url, $this->getHeaders());
        return json_decode($response);
    }

    public function getOperatorsByID($operatorId)
    {
        $url = $this->url . '/operators/' . $operatorId;
        $response = CurlRequest::curlContent($url, $this->getHeaders());
        return json_decode($response);
    }

    public function topUp($amount, $recipient)
    {
        $headers   = [
            "authorization: $this->accessTokenType $this->accessToken",
            "Accept:application/com.reloadly.topups-v1+json",
            'Content-Type:application/json'
        ];

        $data = [
            "operatorId"     => $this->operatorId,
            "amount"         => $amount,
            "useLocalAmount" => $this->useLocalAmount,
            "recipientPhone" => $recipient
        ];

        $data     = json_encode($data);
        $topUpURL = $this->url . '/topups';

        try {
            $response = CurlRequest::curlPostContent($topUpURL, $data, $headers);
            $response = json_decode($response);

            if (@$response->status == 'SUCCESSFUL') {
                return [
                    'status'            => true,
                    'cost'              => $response->balanceInfo->cost,
                    'currencyCode'      => $response->balanceInfo->currencyCode,
                    'custom_identifier' => $response->customIdentifier
                ];
            } else {
                return [
                    'status'  => false,
                    'message' => @$response->message
                ];
            }
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function getHeaders()
    {
        $headers = [
            "authorization: $this->accessTokenType $this->accessToken",
            "Accept:application/com.reloadly.topups-v1+json"
        ];

        return $headers;
    }
}
