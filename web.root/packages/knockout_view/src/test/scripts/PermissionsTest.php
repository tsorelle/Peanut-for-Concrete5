<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/17/2017
 * Time: 6:41 AM
 */

namespace PeanutTest\scripts;


use Concrete\Core\User\Group\Group;
use Tops\concrete5\Concrete5PermissionsManager;

class PermissionsTest extends TestScript
{

    public function execute()
    {
        $manager = new Concrete5PermissionsManager();
        $this->assertNotNull($manager,'class not instantiated');

        $roles = $manager->getRoles();

        $count = sizeof($roles);
        $this->assert($count > 0, 'No roles returned');

        $manager->addRole('Qnut Tester','Peanut tester');
        $roles = $manager->getRoles();
        $actual = sizeof($roles);
        $expected = $count + 1;
        $this->assertEquals($expected,$actual,'Test not added');

        $roles = $manager->getRoles();
        var_dump($roles);
        print "\n\n";

        $manager->removeRole('Qnut Tester');
        $roles = $manager->getRoles();
        $actual = sizeof($roles);
        $expected = $count;
        $this->assertEquals($expected,$actual,'Test not removed');
    }
}