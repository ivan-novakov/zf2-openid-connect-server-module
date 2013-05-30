<?php

namespace PhpIdServer\Authentication\Controller;


class BasicAuthController extends AbstractController
{


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Authentication\Controller\AbstractController::authenticate()
     */
    public function authenticate()
    {
        $request = $this->getRequest();
        
        $authHeader = $request->getHeaders()->get('Authorization');
        
        if (! $authHeader) {
            throw new Exception\AuthenticationException('No authorization header');
        }
        
        $authValue = $authHeader->getFieldValue();
        list ($authType, $authData) = explode(' ', $authValue);
        if ('basic' != strtolower($authType)) {
            throw new Exception\AuthenticationException(sprintf("Unsupported auth type '%s'", $authType));
        }
        
        $decodedValue = base64_decode($authData);
        if (false === $decodedValue) {
            throw new Exception\AuthenticationException(sprintf("Error decoding base64 data '%s'", $authValue));
        }
        
        list ($username, $password) = explode(':', $decodedValue);
        if (! $username || ! $password) {
            throw new Exception\AuthenticationException(sprintf("Invalid authorization string '%s'", $decodedValue));
        }
        
        $users = $this->_getUsers();
        foreach ($users as $user) {
            if ($username == $user['authentication']['username']) {
                if ($password == $user['authentication']['password']) {
                    return $this->getUserFactory()->createUser($user['data']);
                }
                
                throw new Exception\AuthenticationException(sprintf("Invalid password for user '%s'", $username));
            }
        }
        
        throw new Exception\AuthenticationException(sprintf("Unknown user '%s'", $username));
    }


    protected function _getUsers()
    {
        $file = $this->getOption('file');
        if (! file_exists($file)) {
            throw new Exception\AuthenticationException(sprintf("Non-existent user's file '%s'", $file));
        }
        
        $users = require $file;
        if (! is_array($users)) {
            throw new Exception\AuthenticationException(sprintf("Invalid data in '%s'", $file));
        }
        
        return $users;
    }
}