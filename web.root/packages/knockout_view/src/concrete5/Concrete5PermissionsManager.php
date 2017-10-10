<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/15/2017
 * Time: 6:28 PM
 */

namespace Tops\concrete5;


use Concrete\Core\User\Group\Group;
use Concrete\Core\User\Group\GroupList;
use Tops\db\TDBPermissionsManager;
use Tops\sys\IPermissionsManager;
use Tops\sys\TStrings;

class Concrete5PermissionsManager extends TDBPermissionsManager
{
    public static $groupNameFormat = TStrings::wordCapsFormat;
    public static $permissionHandleFormat = TStrings::keyFormat;
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
            $groupName = $group->getGroupName();
            $displayName = $group->getGroupDisplayName();
            $item->Key = TStrings::ConvertNameFormat($groupName,IPermissionsManager::roleKeyFormat);
            $item->Name = TStrings::ConvertNameFormat($groupName,IPermissionsManager::roleNameFormat);
            $item->Description = TStrings::ConvertNameFormat($displayName,IPermissionsManager::roleDescriptionFormat);
            $result[] = $item;
        }
        return $result;
    }

}