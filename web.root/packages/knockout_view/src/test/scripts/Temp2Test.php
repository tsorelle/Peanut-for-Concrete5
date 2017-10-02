<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/27/2017
 * Time: 7:41 AM
 */

namespace PeanutTest\scripts;





use Concrete\Core\Package\ItemCategory\PermissionKey;

class Temp2Test extends TestScript
{

    public function execute()
    {

        $key = 'remove_directory_members';
        $name = 'Remove directory members';
        $permissionName = $key;
        $permissionDescription = $name;
        $pk = \Concrete\Core\Permission\Key\Key::getByHandle($key);
            // PermissionKey::getByHandle($permissionName);
        $ok = $pk->validate();
        $this->assert($ok, 'Permission Not allowed');


    }
}