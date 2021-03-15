<?php

namespace App\MessageServiceProviders;

use Config;


/**
 * Class PushyMessageService
 * @package App\MessageServiceProviders
 */
class DefaultMessageService extends AbstractMessageService
{
    /**
     * @return AbstractMessageService
     * @throws \App\Exceptions\ServiceDisabledException
     */
    protected function getClient()
    {
        $config = $this->getConfigDefault();
        $class  = $config['clientClass'];
        if (!$class) {
            throw new \InvalidArgumentException("Unable to retrieve [clientClass] from [transfergo.messageServices] config");
        }
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Unable to load class [$class]");
        }

        $client = new $class();

        return $client;
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
        $rc = $client->send ($to, $message);

        return $rc;
    }

}
