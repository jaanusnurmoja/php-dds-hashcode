<?php

namespace SK\Digidoc\Example\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class StartController
 *
 * Default view of sample application
 *
 * @package SK\Digidoc\Example\Controller
 */
class StartController implements ControllerProviderInterface
{
    /**
     * Render sample application default view
     *
     * @param \Silex\Application $app
     *
     * @return string
     * @internal param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function main(Application $app)
    {
        return $app['twig']->render('index.twig');
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     *
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $application) {
            return $this->main($application);
        });

        return $controllers;
    }
}
