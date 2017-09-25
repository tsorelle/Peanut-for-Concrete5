<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/15/2017
 * Time: 6:28 PM
 */

namespace Tops\concrete5;


use \Concrete\Core\User\Group\Group;
use Concrete\Core\User\Group\GroupList;
use Tops\db\model\repository\PermissionsRepository;
use Tops\sys\IPermissionsManager;
use Tops\sys\TPermission;
use Tops\sys\TUser;

class Concrete5PermissionsManager implements IPermissionsManager
{
    /***********  Wordpress functions **************************/
    /**
     * @param string $roleName
     * @return bool
     */
    public function addRole($roleName,$roleDescription=null)
    {
        if (empty($roleDescription)) {
            $roleDescription = $roleName;
        }
        $group = Group::add($roleName,$roleDescription);
        return (!empty($group));
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function removeRole($roleName)
    {
        $group = Group::getByName($roleName);
        if (empty($group)) {
            return false;
        }
        $group->delete();
        return true;
    }

    /**
     * @return \stdClass[]
     */
    public function getRoles()
    {
        $result = array();
        $list = new GroupList();;
        $list->includeAllGroups();
        $collection = $list->getResults();
        /**
         * @var $group Group
         */
        foreach ($list->getResults() as $group) {
            $item = new \stdClass();
            $item->Name = $group->getGroupDisplayName();
            $item->Value = $group->getGroupName();
            $result[] = $item;
        }
        return $result;
    }


    /******** Tops functions **********************/

    /**
     * @var PermissionsRepository
     */
    private $permissionsRepository;

    private function getRepository()
    {
        if (!isset($this->permissionsRepository)) {
            $this->permissionsRepository = new PermissionsRepository();
        }
        return $this->permissionsRepository;
    }


    /**
     * @return TPermission[]
     */
    public function getPermissions()
    {
        return $this->getRepository()->getAll();
    }

    public function getPermission($permissionName)
    {
        return $this->getRepository()->getPermission($permissionName);
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function assignPermission($roleName, $permissionName)
    {
        return $this->getRepository()->assignPermission($roleName,$permissionName);
    }

    public function addPermission($name, $description)
    {
        $username = TUser::getCurrent()->getUserName();
        $this->getRepository()->addPermission($name,$description,$username);
        return true;
    }


    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function revokePermission($roleName, $permissionName)
    {
        return $this->getRepository()->revokePermission($roleName,$permissionName);
    }

    public function removePermission($name)
    {
        return $this->getRepository()->removePermission($name);
    }
}