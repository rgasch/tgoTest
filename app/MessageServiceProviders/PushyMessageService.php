<?php

namespace App\MessageServiceProviders;

use App\MessageServiceProviders\Clients\PushyClient;
use Twilio\Rest\Client;
use Config;


/**
 * Class PushyMessageService
 * @package App\MessageServiceProviders
 */
class PushyMessageService extends AbstractMessageService
{
    /**
     * @var string
     */
    private $serviceConfigKey = 'pushy';

    /**
     * @return curl handle
     * @throws \App\Exceptions\ServiceDisabledException
     */
    protected function getClient()
    {
        $config = $this->getConfig($this->serviceConfigKey);

        return PushyClient::getClient($config['token']);
    }


    /**
     * @param string $to
     * @param string $message
     * @return mixed
     * @throws \App\Exceptions\ServiceDisabledException
     */
    public function send(string $to, string $message)
    {
        $client = $this->getClient();

        $rc = PushyClient::send($client, $to, $message);

        $this->store($to, $message);
        return $rc;
    }

}
