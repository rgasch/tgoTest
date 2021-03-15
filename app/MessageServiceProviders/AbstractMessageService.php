<?php

namespace App\MessageServiceProviders;

use App\Exceptions\ServiceDisabledException;
use App\Models\Message;
use Config;


/**
 * Class AbstractMessageProvider
 * @package App\MessageServiceProviders
 */
abstract class AbstractMessageService
{
    /**
     * @return mixed
     */
    protected abstract function getClient();

    /**
     * @param string $to
     * @param string $message
     * @return mixed
     */
    public abstract function send(string $to, string $message);


    /**
     * @param string $serviceName
     * @return array
     * @throws \InvalidArgumentException
     * @throws ServiceDisabledException
     */
    protected function getConfig(string $serviceName) : array
    {
        $config = Config::get("transfergo.messageServices.$serviceName");
        if (!$config) {
            throw new \InvalidArgumentException("Unable to retrieve valid configuration from [transfergo.messageServices.$serviceName] config");
        }

        if (!$config['enabled']) {
            throw new ServiceDisabledException("MessageService [$serviceName] is disabled in [transfergo.messageServices.$serviceName] config");
        }

        return $config;
    }


    /**
     * Retrieve the config for the default MessageServiceProvider
     * @throws \InvalidArgumentException
     * @throws ServiceDisabledException
     * @return array
     */
    protected function getConfigDefault() : array
    {
        $configs = Config::get("transfergo.messageServices");
        if (!$configs) {
            throw new \InvalidArgumentException("Unable to retrieve valid configuration array from [transfergo.messageServices] config");
        }

        foreach ($configs as $k=>$v) {
            $default = $v['default'] ?? false;
            if ($default) {
                // If default is not enabled, throw exception
                if (!$v['enabled']) {
                    throw new ServiceDisabledException("MessageService [$k] is disabled in [transfergo.messageServices.$serviceName] config");
                }
                return $v;
            }
        }

        throw new \InvalidArgumentException("Unable to retrieve valid default configuration from [transfergo.messageServices] config");
    }


    /**
     * Retrieve the config for the given priority
     * @throws \InvalidArgumentException
     * @return array
     */
    protected function getConfigByPriority(int $priority) : array
    {
        $configs = Config::get("transfergo.messageServices");
        if (!$configs) {
            throw new \InvalidArgumentException("Unable to retrieve valid configuration array from [transfergo.messageServices] config");
        }

        foreach ($configs as $k=>$v) {
            $priorityConfig = (int)($v['priority'] ?? -1);
            if ($priorityConfig == $priority) {
                // If service is not enabled, try the next priority
                if (!$v['enabled']) {
                    return $this->getConfigByPriority($priority+1);
                }
                return $v;
            }
        }

        throw new \InvalidArgumentException("Unable to retrieve valid configuration from [transfergo.messageServices] config for priority [$priority]");
    }


    /**
     * @param string $service
     * @param string $to
     * @param string $body
     * @return Message
     */
    protected function store(string $service, string $to, string $body): Message
    {
        $message = new Message();
        $message->service = $service;
        $message->to      = $to;
        $message->message = $body;
        $message->save();

        return $message;
    }
}
