<?php
namespace SK\Digidoc\Example\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Load in existing or create new DDOC or BDOC container
 *
 * Class ContainerController
 *
 * @package SK\Digidoc\Example\Controller
 */
class ContainerController implements ControllerProviderInterface
{

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->post('/new', function (Application $app) {
            return $app['twig']->render('document_info.twig', ['action' => 'new']);
        });

        $controllers->post('/existing', function (Application $app) {
            return $app['twig']->render('document_info.twig', ['action' => 'existing']);
        });

        return $controllers;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     */
    public function createNewContainer(Request $request, Application $app)
    {

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     */
    public function loadExistingContainer(Request $request, Application $app)
    {

    }
}