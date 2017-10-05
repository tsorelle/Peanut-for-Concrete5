<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 10/4/2017
 * Time: 6:35 AM
 */

namespace PeanutTest\scripts;


use Tops\concrete5\Concrete5PermissionsManager;
use Tops\sys\TStrings;
use Tops\sys\TUser;

class UsersetupTest extends TestScript
{

    private $roles;
    private $continueTest = false;
    /**
     * @var Concrete5PermissionsManager
     */
    private $manager;

    private function roleExists($value) {
        if (!isset($this->roles)) {
            if ($this->getRoleCount() === 0) {
                return false;
            }
        }
        $value = TStrings::convertNameFormat($value,TStrings::keyFormat);
        foreach ($this->roles as $role) {
            if ($role->Value === $value) {
                return true;
            }
        }
        return false;
    }

    private function getRoleCount() {
        $this->roles = $this->manager->getRoles();
        $count = sizeof($this->roles);
        $this->assert($count > 0, 'No roles returned');
        return $count;
    }

    private function addRole($roleName,$roleCount,$fail=false) {
        $hasRole = $this->roleExists($roleName);
        $this->manager->addRole($roleName);
        $expected = $hasRole ? $roleCount : $roleCount + 1;
        $actual = $this->getRoleCount();
        $msg = ($expected == $actual) ?
             'duplicate role created' : 'role not added';
        $result = $this->assertEquals($expected,$actual,$msg);
        if ($fail && !$result) {
            exit;
        }
        $this->continueTest = $result;
        if ($result) {
            print  ($actual > $roleCount ?  "Role '$roleName' added.\n" : "Role '$roleName' exists.\n");
        }
        return $actual;
    }

    private function removeRole($roleName,$roleCount) {
        $this->manager->removeRole($roleName);
        $actual = $this->getRoleCount();
        print ( $actual < $roleCount ? "Role '$roleName' removed.\n" : "Role '$roleName' not removed.\n" );
        return $actual;
    }
    public function execute()
    {
        $this->manager = new Concrete5PermissionsManager();
        $roleCount = $this->getRoleCount();

        $testRole = 'test role';
        $roleCount = $this->addRole($testRole,$roleCount,true);
        $roleCount = $this->removeRole($testRole,$roleCount);
        $roleCount = $this->addRole(TUser::appAdminRoleName,$roleCount);
        $roleCount = $this->addRole(TUser::mailAdminRoleName,$roleCount);
        $this->manager->assignPermission(TUser::mailAdminRoleName,TUser::mailAdminPermissionName);
        $this->manager->assignPermission(TUser::appAdminPermissionName,TUser::mailAdminPermissionName);

        print "\n".($this->continueTest ? 'Ready for "user" test. Add your test user to the mail admin role' : 'Setup failed')."\n";

    }
}