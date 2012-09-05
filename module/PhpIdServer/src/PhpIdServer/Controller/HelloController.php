<?php
namespace PhpIdServer\Controller;
use PhpIdServer\Metadata\Storage;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class HelloController extends AbstractActionController
{


    public function worldAction ()
    {
        _dump($this->getServiceLocator()->get('serverConfig'));
        $m = new Storage();
        _dump($m);
        
        $message = $this->params()
            ->fromQuery('message', 'foo');
        
        return array(
            'message' => 'Nazdar'
        );
        $view = new ViewModel(array(
            'message' => $message
        ));
        
        //$view->setTerminal(true);
        

        return $view;
    }
}