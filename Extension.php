<?php

namespace Bolt\Extension\sahassar\remoteimage;

class Extension extends \Bolt\BaseExtension
{
    public function getName()
    {
        return "remoteimage";
    }

    public function initialize()
    {
        if ($this->app['config']->getWhichEnd()=='frontend') {
            $this->addTwigFunction('remoteimage', 'remoteImage', array('is_variadic' => true));
        }
    }
    
    public function remoteImage(array $args = array())
    {
        $defaults = array(
              'url' => '',
        );
        $args = array_merge($defaults, $args);
        if(empty($args['url'])){
            return "no url set";
        }
        
        $parsedURL = parse_url($args['url']);
        $pathinfo = pathinfo($parsedURL['path']);
        
        if(!in_array($pathinfo['extension'], $this->app['config']->get('general/accept_file_types'))){
            return "not an accepted file type";
        }
        
        $path = $this->app['paths']['filespath'] . '/remotecache/' . join(explode('/', $parsedURL['path']));
        $returnurl = 'remotecache/' . join(explode('/', $parsedURL['path']));
        
        if(!is_dir($this->app['paths']['filespath'] . '/remotecache')){
            mkdir($this->app['paths']['filespath'] . '/remotecache');
        }
        
        if (!is_file($path)) {
            $opts = [
                'save_to' => $path,
            ];
            $this->app['guzzle.client']->get($args['url'], $opts)->getBody(true);
        }
        return $returnurl;
    }
}
