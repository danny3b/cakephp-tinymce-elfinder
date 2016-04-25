<?php
namespace CakephpTinymceElfinder\Controller;

use CakephpTinymceElfinder\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Core\Exception\Exception;

class ElfindersController extends AppController
{

    public function elfinder()
    {
        $this->viewBuilder()->layout('elfinder');
        $connectorUrl = Router::url('/cakephp-tinymce-elfinder/Elfinders/connector');
        
        $configOptions = Configure::read('TinymceElfinder');

        $clientOptions = array();
        if (!empty($configOptions['client_options'])) {
            $clientOptions = $configOptions['client_options'];
        }
        
        $commandList = array();
        if (empty($configOptions['commands'])) {
            $commandList = $configOptions['commands'];
        }
        
        $staticFiles = array();
        if (!empty($configOptions['static_files'])) {
            $staticFiles = $configOptions['static_files'];
        }
        
        if (empty($staticFiles['js']['jquery']) || empty($staticFiles['js']['jquery_ui']) || empty($staticFiles['css']['jquery_ui'])) {
            throw new Exception('Jquery min js, Jquery UI js and Jquery UI css files paths are required');
        }

        $this->set(compact('connectorUrl', 'clientOptions', 'commandList', 'staticFiles'));
    }
    
    // run elFinder
    public function connector(){
        $this->autoRender = false;
    
        $options = Configure::read('TinymceElfinder.options');

        $connector = new \elFinderConnector(new \elFinder($options));
        $connector->run();
    }
    
    
}