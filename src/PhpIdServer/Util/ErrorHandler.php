<?php

namespace PhpIdServer\Util;

use Zend\Log\Logger;


class ErrorHandler
{

    /**
     * @var Logger
     */
    protected $logger = null;


    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }


    public function logException(\Exception $e)
    {
        $trace = $e->getTraceAsString();
        $i = 1;
        do {
            $messages[] = $i ++ . ": " . $e->getMessage();
        } while (($e = $e->getPrevious()) != false);
        
        $log = "Exception:\n" . implode("\n", $messages);
        $log .= "\nTrace:\n" . $trace;
        
        $this->logError($log);
    }


    public function logError($message)
    {
        $this->logger->err($message);
        error_log($message);
    }
}