<?php

namespace sahassar\remoteimage;

use Bolt\Extension\SimpleExtension;

class remoteimageExtension extends SimpleExtension
{

    protected function registerTwigFunctions()
    {
        return [
            'remoteimage' => ['remoteImage', ['is_variadic' => true]]
        ];
    }

    public function remoteImage(array $args = array())
    {
        $app = $this->getContainer();
        $opts = [];
        $defaults = array(
            'url' => '',
        );

        $args = array_merge($defaults, $args);

        if (empty($args['url'])) {
            return "no url set";
        }

        $parsedURL = parse_url($args['url']);

        $returnurl = 'remoteimage/' . hash('sha256', $parsedURL['host'] . $parsedURL['path'] . $parsedURL['query']) . '.jpg';

        $fs = $app['filesystem']->getFilesystem('files');

        if (!$fs->has('remoteimage')) {
            $fs->createDir('remoteimage');
        }

        if (!$fs->has($returnurl)) {
            $image = $app['guzzle.client']->get($args['url'], $opts)->getBody(true);
            $fs->put($returnurl, $image);
        }
        return $returnurl;
    }
}

