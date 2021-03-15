<?php


namespace App\MessageServiceProviders\Clients;


/**
 * Class PushyClient
 * @package App\MessageServiceProviders\Clients
 */
class PushyClient
{

    /**
     * @param $token
     * @param array $options
     * @return \CurlHandle|false|resource
     * @throws \Exception
     */
    static public function getClient ($token, $options=[])
    {
        // Set Content-Type header since we're sending JSON
        $headers = [ 'Content-Type: application/json' ];

        // Initialize curl handle
        $ch = curl_init();
        if (!$ch) {
            throw new \Exception ("Unable to instantiate curl handler");
        }

        // Set URL to Pushy endpoint
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushy.me/push?api_key=' . $token);

        // Set request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set our custom headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Get the response back as string instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $ch;
    }


    /**
     * @param $ch Curl handle
     * @param $data
     * @param $to
     * @return mixed
     * @throws \Exception
     */
    static public function send($ch, $data, $to)
    {
        $post = [];
        $post['to']   = $to;
        $post['data'] = $data;

        // Set post data as JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));

        // Actually send the push
        $result = curl_exec($ch);

        // Display errors
        if (curl_errno($ch)) {
            throw new \Exception (curl_error($ch));
        }

        // Close curl handle
        curl_close($ch);

        // Attempt to parse JSON response
        $response = @json_decode($result);

        // Throw if JSON error returned
        if (isset($response) && isset($response->error)) {
            throw new \Exception('Pushy API returned an error: ' . $response->error);
        }

        return $response;
    }
}
