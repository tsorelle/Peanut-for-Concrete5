<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/27/2017
 * Time: 7:41 AM
 */

namespace PeanutTest\scripts;


use Concrete\Core\Package\Package;
use Concrete\Core\Package\PackageList;
use \Concrete\Core\User\Group\Group;
use \Concrete\Core\Permission\Access\Entity\GroupEntity as GroupPermissionAccessEntity;
use \Concrete\Core\Permission\Access\Entity\Entity as PermissionAccessEntity;
use \Concrete\Core\Permission\Access\Access as PermissionAccess;
use \Concrete\Core\Support\Facade\Package as PackageFacade;
use Tops\concrete5\Concrete5PermissionsManager;
use Tops\sys\TUser;


class TempTest extends TestScript
{

    public function execute()
    {

        $user = TUser::getCurrent();
        $name = $user->getUserName();
        $admin = $user->isAdmin() ? 'Administrator' : 'Non-administrator';

        print "Succeed if user is administrator\n";
        print "Running test as: $name ($admin) \n";


        $manager = new Concrete5PermissionsManager();

        $roles = $manager->getRoles();

        $count = sizeof($roles);
        $this->assert($count > 0, 'No roles returned');

        $testRoleName = Permissions1Test::TestRoleName;
        $testPermissionName = Permissions1Test::TestPermissionName;

        $manager->assignPermission($testRoleName, $testPermissionName);
    }
}