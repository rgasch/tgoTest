<?php

namespace App\MessageServiceProviders;

use Twilio\Rest\Client;
use Config;


/**
 * Class TwilioMessageService
 * @package App\MessageServiceProviders
 */
class TwilioMessageService extends AbstractMessageService
{
    /**
     * @var string
     */
    private $serviceConfigKey = 'twilio';

    /**
     * @return Client
     * @throws \App\Exceptions\ServiceDisabledException
     */
    protected function getClient()
    {
        $config = $this->getConfig($this->serviceConfigKey);

        return new Client($config['sid'], $config['token']);
    }


    /**
     * @param string $to
     * @param string $message
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance
     * @throws \App\Exceptions\ServiceDisabledException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function send(string $to, string $message)
    {
        $client = $this->getClient();
        $config = $this->getConfig($this->serviceConfigKey);

        $rc = $client->messages->create($to, [
            'from' => $config['from'],
            'body' => $message
        ]);

        $this->store($this->serviceConfigKey, $to, $message);
        return $rc;
    }
}
