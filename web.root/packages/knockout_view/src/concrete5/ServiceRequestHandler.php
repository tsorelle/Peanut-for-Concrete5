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
use Concrete\Core\Http\Request;

use Peanut\sys\ViewModelPageBuilder;
use Tops\services\ServiceFactory;

class ServiceRequestHandler extends Controller
{
    public function executeService()
    {
        $response = ServiceFactory::Execute();
        print json_encode($response);
    }

    public function runtest($testname) {
        print "<pre>";
        print "Running $testname\n";
        if (empty($testname)) {
            exit("No test name!");
        }
        $testname = strtoupper(substr($testname,0,1)).substr($testname,1);
        $className = "\\PeanutTest\\scripts\\$testname".'Test';
        $test = new $className();
        $test->run();
        print "</pre>";
        exit;
    }

    public function getSettings() {
        include(__DIR__."/../../../../application/config/settings.php");
    }

    public function buildPage()  {

        $pageName = Request::getInstance()->get('vmname');
        $content = ViewModelPageBuilder::Build($pageName);
        print $content;
    }

}