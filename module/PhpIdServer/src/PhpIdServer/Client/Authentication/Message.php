<?php
namespace PhpIdServer\Client\Authentication;


class Message extends \Zend\Stdlib\Message
{


    public function setAuthenticated ()
    {
        $this->setMetadata(array(
            'authenticated' => true, 
            'reason' => ''
        ));
    }


    public function setNotAuthenticated ($reason)
    {
        $this->setMetadata(array(
            'authenticated' => false, 
            'reason' => $reason
        ));
    }


    public function isAuthenticated ()
    {
        return $this->getMetadata('authenticated');
    }


    public function getNotAuthenticatedReason ()
    {
        return $this->getMetadata('reason');
    }
}