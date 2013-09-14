<?php

namespace SmartRock\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\SecurityExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;

/**
 * Asset twig extension provider for Silex
 *
 * @author Tobal San <tobalsan@gmail.com>
 */
class TwigAssetServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['twig'] = $app->share(function ($app) {
            $app['twig.options'] = array_replace(
                array(
                    'charset'          => $app['charset'],
                    'debug'            => $app['debug'],
                    'strict_variables' => $app['debug'],
                ), $app['twig.options']
            );

            $twig = new \Twig_Environment($app['twig.loader'], $app['twig.options']);
            $twig->addGlobal('app', $app);
            $twig->addExtension(new \Silex\Provider\TwigCoreExtension());
            $twig->addExtension(new \SmartRock\Twig\AssetTwigExtension($app['request']));

            if ($app['debug']) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            if (class_exists('Symfony\Bridge\Twig\Extension\RoutingExtension')) {
                if (isset($app['url_generator'])) {
                    $twig->addExtension(new RoutingExtension($app['url_generator']));
                }

                if (isset($app['translator'])) {
                    $twig->addExtension(new TranslationExtension($app['translator']));
                }

                if (isset($app['security'])) {
                    $twig->addExtension(new SecurityExtension($app['security']));
                }

                if (isset($app['form.factory'])) {
                    $app['twig.form.engine'] = $app->share(function ($app) {
                        return new TwigRendererEngine($app['twig.form.templates']);
                    });

                    $app['twig.form.renderer'] = $app->share(function ($app) {
                        return new TwigRenderer($app['twig.form.engine'], $app['form.csrf_provider']);
                    });

                    $twig->addExtension(new FormExtension($app['twig.form.renderer']));

                    // add loader for Symfony built-in form templates
                    $reflected = new \ReflectionClass('Symfony\Bridge\Twig\Extension\FormExtension');
                    $path = dirname($reflected->getFileName()).'/../Resources/views/Form';
                    $app['twig.loader']->addLoader(new \Twig_Loader_Filesystem($path));
                }
            }

            return $twig;
        });
    }

    public function boot(Application $app)
    {
    }
}