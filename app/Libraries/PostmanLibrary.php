<?php

namespace App\Libraries;

use Unirest\Exception;
use Unirest\Request as UniRequest;
use Unirest\Request\Body as UniBody;

class PostmanLibrary
{
    public static function postJson($url, $accessToken, $data = [])
    {
        $headers = array(
            'Authorization' => "Bearer $accessToken",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        );
        try {
            $body = UniBody::json($data);
            $curlOptions = array(
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSLVERSION => 6,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 60
            );
            UniRequest::curlOpts($curlOptions);
            UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
            UniRequest::verifyPeer(false);
            $response = UniRequest::post($url, $headers, $body);

            return $response;
        } catch (Exception $e) {
            return new Exception($e->getMessage());
        }
    }

    /**
     * Get JSON.
     *
     * @param $url
     * @param $accessToken
     * @return \Unirest\Response
     */
    public static function getJson($url, $accessToken)
    {
        $headers = array(
            'Authorization' => "Bearer $accessToken",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        );
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false);
        $response = UniRequest::get($url, $headers, null);

        return $response;
    }

    public static function deleteJson($url, $accessToken, $body = null)
    {
        $headers = array(
            'Authorization' => "Bearer $accessToken",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        );
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false);
        $response = UniRequest::delete($url, $headers, $body);

        return $response;
    }

    public static function putJson($url, $accessToken, $data)
    {
        $headers = array(
            'Authorization' => "Bearer $accessToken",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        );
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false);
        $body = UniBody::json($data);
        $response = UniRequest::put($url, $headers, $body);

        return $response;
    }
}