<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/29/2016
 * Time: 7:06 AM
 */
/**
 * File Location: (doc root)\application\src\tops\services\ServiceRequestHandler.php
 *
 * Add autoloader in \application\bootstrap\app.php
 *
 * $classLoader = new \Symfony\Component\ClassLoader\Psr4ClassLoader();
 * // your application location
 * $classLoader->addPrefix('Application\\Aftm', DIR_APPLICATION . '/' . DIRNAME_CLASSES . '/aftm');
 * // tops library location
 * $classLoader->addPrefix('Application\\Tops', DIR_APPLICATION . '/' . DIRNAME_CLASSES . '/tops');
 * $classLoader->register();
 *
 * Declare routes in \application\bootstrap\app.php
 *
 * Route::register(
 * '/tops/service/execute',
 * 'Application\Tops\services\ServiceRequestHandler::executeService'
 * );
 *
 * Route::register(
 * '/tops/service/execute/{sid}',
 * 'Application\Tops\services\ServiceRequestHandler::executeService'
 * );
 *
 * Route::register(
 * '/tops/service/execute/{sid}/{arg}',
 * 'Application\Tops\services\ServiceRequestHandler::executeService'
 * );
 *
 */
namespace Tops\concrete5;

use Concrete\Core\Controller\Controller;


use Tops\services\ServiceFactory;

class ServiceRequestHandler extends Controller
{
    public function executeService()
    {
        $response = ServiceFactory::Execute();
        print json_encode($response);
    }


    public function getSettings() {
        include(__DIR__."/../../../../application/config/settings.php");
    }

}