<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/18/2017
 * Time: 7:47 AM
 */

namespace PeanutTest\scripts;


use Tops\concrete5\Concrete5PermissionsManager;

class RollbackpermissionstestTest extends TestScript
{

    public function execute()
    {
        $manager = new Concrete5PermissionsManager();
        $this->assertNotNull($manager,'class not instantiated');

        $manager->removeRole('Qnut Tester');
        $roles = $manager->getRoles();
        var_dump($roles);
    }
}