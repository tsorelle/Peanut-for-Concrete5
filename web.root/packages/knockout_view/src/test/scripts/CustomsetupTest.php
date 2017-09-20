<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/18/2017
 * Time: 6:55 AM
 */

namespace PeanutTest\scripts;


use Tops\concrete5\Concrete5PeanutInstaller;
use Tops\sys\TPath;

class CustomsetupTest extends TestScript
{

    public function execute()
    {
        $bootstrapPath = TPath::fromFileRoot('application/bootstrap');
        // unlink("$bootstrapPath/app.php");
        // copy("$bootstrapPath/original-app.txt","$bootstrapPath/app.php");
        $installer = new Concrete5PeanutInstaller();
        $installer->doCustomSetup();
    }
}