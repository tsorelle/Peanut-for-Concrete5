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
use Tops\sys\TStrings;
use Tops\sys\TUser;
use \Concrete\Core\Permission\Access\Entity\GroupEntity as GroupPermissionAccessEntity;
use \Concrete\Core\Permission\Access\Access as PermissionAccess;


class Concrete5PermissionsManager implements IPermissionsManager
{
    public static $groupNameFormat = TStrings::wordCapsFormat;
    public static $permissionKeyFormat = TStrings::keyFormat;
    public static $permissionNameFormat = TStrings::initialCapFormat;

    /***********  Concrete5 functions **************************/
    /**
     * @param string $roleName
     * @return bool
     */
    public function addRole($roleName,$roleDescription=null)
    {
        $roleName = TStrings::convertNameFormat($roleName,self::$groupNameFormat);
        if (empty($roleDescription)) {
            $roleDescription = $roleName;
        }
        $group = Group::getByName($roleName);
        if (empty($group)) {
            $group = Group::add($roleName,$roleDescription);
        }
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
        $key = TStrings::convertNameFormat($permissionName,self::$permissionKeyFormat);
        $repository = $this->getRepository();
        $repository->assignPermission($roleName,$key);
        $permission = $repository->getPermission($permissionName);
        if ($permission === false) {
            return false;
        }
        $roles = $permission->getRoles();
        $this->assignPermissionGroups($key,$roles);
        return true;
    }

    private function assignPermissionGroups( $permissionKey, array $roles = [])
    {
        /**
         * @var $pkObject \Concrete\Core\Permission\Key\Key
         */
        $pkObject = \Concrete\Core\Permission\Key\Key::getByHandle($permissionKey);
        $pt = $pkObject->getPermissionAssignmentObject();
        if (empty($roles)) {
            $pt->clearPermissionAssignment();
            return;
        }

        /**
         * @var $pa PermissionAccess
         */
        $pa = PermissionAccess::create($pkObject);
        foreach ($roles as $roleName) {

            $group = Group::getByName($roleName);
            /**
             * @var $groupEntity GroupPermissionAccessEntity
             */
            $groupEntity = GroupPermissionAccessEntity::getOrCreate($group);

            $pa->addListItem($groupEntity);
        }

        /**
         * @var $pt \Concrete\Core\Permission\Assignment\Assignment
         */
        $pt->assignPermissionAccess($pa);
    }


    public function addPermission($name, $description)
    {
        $username = TUser::getCurrent()->getUserName();
        $permission = $this->getRepository()->getPermission($name);
        if ($permission === false) {
            $this->getRepository()->addPermission($name, $description, $username);
            $this->createPermission($name);
        }
        return true;
    }


    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function revokePermission($roleName, $permissionName)
    {
        $key = strtolower(str_replace(' ','_',$permissionName));
        $this->getRepository()->revokePermission($roleName,$key);
        $permission = $this->getRepository()->getPermission($permissionName);
        $roles = $permission->getRoles();
        $this->assignPermissionGroups($key,$roles);
        return true;
    }

    public function removePermission($name)
    {
        return $this->getRepository()->removePermission($name);
        // todo: remove c5 permission
    }

    public function createPermission($permissionName) {
        $handle = TStrings::convertNameFormat($permissionName,self::$permissionKeyFormat);
        $name = TStrings::convertNameFormat($permissionName,self::$permissionNameFormat);
        $existing = \Concrete\Core\Permission\Key\Key::getByHandle($handle);
        if (empty($existing)) {
            \Concrete\Core\Permission\Key\Key::add('admin', $handle, $name, '', false, false);
        }
    }

    public function verifyPermission($permissionName)
    {
        $handle = TStrings::convertNameFormat($permissionName,self::$permissionKeyFormat);
        $pk = $pk = \Concrete\Core\Permission\Key\Key::getByHandle($handle);
        if ($pk !== null) {
            return $pk->validate();
        }
        return false;

    }
}