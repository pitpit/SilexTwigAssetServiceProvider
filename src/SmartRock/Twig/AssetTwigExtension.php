<?php

namespace SmartRock\Twig;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AssetTwigExtension extends \Twig_Extension
{
    private $app;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getName()
    {
        return 'AssetTwigExtension';
    }

    public function getFunctions()
    {
        return array('asset' => new \Twig_Function_Method($this, 'asset'));
    }

    public function asset($url) {
        return sprintf('%s/%s', $this->request->getBasePath(), ltrim($url, '/'));
    }
}