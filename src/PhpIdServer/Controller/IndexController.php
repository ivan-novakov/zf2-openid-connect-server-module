<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace PhpIdServer\Controller;
use Zend\Mvc\Controller\AbstractActionController;


class IndexController extends AbstractActionController
{


    public function indexAction ()
    {
        $response = $this->getResponse();
        $response->setContent('INDEX');
        
        return $response;
    }


    public function fooAction ()
    {
        $response = $this->getResponse();
        $response->setContent('FOO');
        
        return $response;
    }
}
