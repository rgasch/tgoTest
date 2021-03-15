## TransferGo Test Assignment (Robert Gasch)

This system implements an abstracted message sending service which provides a common interface against 
different message service providers. 


### Installation

- git clone ...
- cd TransferGo
- create mysql database: *create database SOME_DB_NAME;*
- edit .env to match your database credentials
- php artisan migrate

The system is now installed, you can now test it (see *Testing using CommandLineJobs* section)


### Configuration
The file *config/transfergo.php* contains an array of definitions of various message service providers. These 
definitions contain things like 

- API tokens
- enabled flag (whether a not a service is enabled
- priority (ascending integer, default service should have priority 0)
- client class (the class to instantiate the message when fallback is used)


### Architecture
The system is based around the *App\MessageServiceProviders\AbstractMessageService* class which provides
the following base methods: 

- getConfig ($serviceName): retrieve the config array for the given service name (example: 'twilio')
- getConfigDefault: retrieve the config array for the default service
- getConfigByPriority: retrieve the config array for the current invocation/iteration priority (uses internal static integer to keep track of the current priority)
- store: save a sent message to the database

The *App\MessageServiceProviders\AbstractMessageService* interface requires classes extending this class to 
implement the following methods: 

- protected function getClient();
- public function send(string $to, string $message);

If a MessageProvider does not provide an interface through composer, a client library should be provided in 
*app\MessageServiceProvider\Clients*. 


### Database
The system implements one custom migration which creates the *messages* table, used to store sent messages


### Testing using CommandLineJobs
The following artisan jobs have been provided: 

- *SendMessage*: attempts to send a message using the default service provider implementation. If this fails, 
it will retrieve additional service providers based on increasing priorities until a message is successfully 
sent. This job is currently hardcoded to exit after 3 priority-based send-attempts. 
- *SentMessages*: displays the last 10 sent messages as retrieved from the database
- *TestMessage*: attempts to send a message using a hardcoded client (used for dev-testing)
