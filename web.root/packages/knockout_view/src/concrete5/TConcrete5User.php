<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 3/30/2017
 * Time: 7:17 AM
 */

// todo Complete and test user implementation

namespace Tops\concrete5;


use Core;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;
use Concrete\Core\Attribute\Category\CategoryService;
use Concrete\Core\Attribute\Key\UserKey;
use Concrete\Core\User\UserInfoRepository;
use Tops\cache\ITopsCache;
use Tops\cache\TSessionCache;
use Tops\db\model\repository\PermissionsRepository;
use Tops\sys\TAbstractUser;
use Tops\sys\TConfiguration;
use Tops\sys\TPermission;
use Tops\sys\TStrings;
use Tops\sys\TUser;

/**
 * Class TConcrete5User
 * @package Tops\sys
 *
 * see https://documentation.concrete5.org/developers/users-groups/reading-data-existing-users
 */
class TConcrete5User extends TAbstractUser
{
    /**
     * @var PermissionsRepository
     */
    private $permissionsRepository;

    /**
     * @return PermissionsRepository
     */
    private function getRepository()
    {
        if (!isset($this->permissionsRepository)) {
            $this->permissionsRepository = new PermissionsRepository();
        }
        return $this->permissionsRepository;
    }


    /**
     * @param $permissionName
     * @return bool|TPermission
     */
    public function getPermission($permissionName)
    {
        return $this->getRepository()->getPermission($permissionName);
    }



    /**
     * @var $user User
     */
    private $user;

    /**
     * @var $userInfo UserInfo
     */
    private $userInfo;

    private $memberGroups;

    private static $userController;


    private static function getUserController() {
        if (!isset(self::$userController)) {
            /**
             * @var $service CategoryService;
             */
            $service = \Core::make(CategoryService::class);
            self::$userController = $service->getByHandle('user')->getController();
        }
        return self::$userController;
    }

    public static function getAttributeList() {
        return
            [
                 TUser::profileKeyFullName  =>
                    [
                        'akHandle' => self::formatC5AttributeKey(TUser::profileKeyFullName),
                        'akName' => 'Full name',
                        'akIsSearchable' => true,
                        'akIsSearchableIndex' => true,
                    ],
                TUser::profileKeyShortName  =>
                    [
                        'akHandle' => self::formatC5AttributeKey(TUser::profileKeyFullName),
                        'akName' => 'Short name',
                        'akIsSearchable' => true,
                        'akIsSearchableIndex' => true,
                    ],
                TUser::profileKeyDisplayName  =>
                    [
                        'akHandle' => self::formatC5AttributeKey(TUser::profileKeyDisplayName),
                        'akName' => 'Display name',
                        'akIsSearchable' => true,
                        'akIsSearchableIndex' => true,
                    ]
            ];
    }

    public static function CreateAttributeKeys($pkg = false) {
        $list = self::getAttributeList();
        $controller = self::getUserController();
        $type='text';
        foreach  ($list as $key => $args ) {
            $handle = $args['akHandle'];
            if (UserKey::getByHandle($handle) === null) {
                $controller->add($type, $args, $pkg);
            }
        }
    }

    /**
     * @return UserInfoRepository
     * @throws \Exception
     */
    private function getUserInfoRepository() {
        $repo = Core::make(UserInfoRepository::class);
        if (empty($repo)) {
            throw new \Exception('Cannot create user info repository');
        }
        return $repo;
    }


    private function setUser($user,$userInfo=null)
    {
        unset($this->isCurrentUser);
        if ($user == null) {
            $this->userInfo = null;
            $this->id = 0;
            unset($this->userName);
            return false;
        }
        $this->user = $user;
        $this->id = $this->user->getUserID();
        if ($userInfo === null) {
            $this->userInfo = $this->getUserInfoRepository()->getByID($this->id);
        }
        else {
            $this->userInfo = $userInfo;
        }
        $this->userName = $this->user->getUserName();
        unset($this->memberGroups);
        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function loadById($id)
    {
        return $this->setUser(User::getByUserID($id));
    }

    /**
     * @param $userName
     * @return mixed
     */
    public function loadByUserName($userName)
    {
        $userInfo = $this->getUserInfoRepository()->getByName($userName);
        return $this->loadFromUserInfo($userInfo);

    }
    /**
     * @param $email
     * @return mixed
     */
    public function loadByEmail($email)
    {
        $userInfo = $this->getUserInfoRepository()->getByEmail($email);
        return $this->loadFromUserInfo($userInfo);
    }



    /**
     * @param $userInfo UserInfo
     * @return bool
     */
    private function loadFromUserInfo($userInfo) {
        if ($userInfo == null) {
            return $this->setUser(null);
        }
        return $this->setUser(User::getByUserID($userInfo->getUserID()),$userInfo);
    }

    /**
     * @return mixed
     */
    public function loadCurrentUser()
    {
        $result = $this->setUser(new User());
        $this->isCurrentUser = true;
        return $result;
    }

    public function isCurrent()
    {
        if (!isset($this->isCurrentUser)) {
            $current = new User();
            $this->isCurrentUser = ($this->id === $current->getUserID());
        }
        return $this->isCurrentUser;
    }

    /**
     * @param $roleName
     * @return bool
     */
    public function isMemberOf($roleName)
    {
        $groups = $this->getRoles();
        $roleName = TStrings::convertNameFormat($roleName,Concrete5PermissionsManager::$groupNameFormat);
        return in_array($roleName,$groups);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->user->getUserID();
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->user->checkLogin();
    }

    /**
     * @param string $value
     * @return bool
     *
     * Use datasbase permission manager until C5 assignPermission is fixed.
     */
    public function isAuthorized($value = '')
    {
        $authorized = parent::isAuthorized($value);
        if (!$authorized) {
//            if ($this->isCurrentUser) {
//                // Concrete 5 only supports permission check for current user.
//                $value = TStrings::convertNameFormat($value, Concrete5PermissionsManager::$permissionKeyFormat);
//                $pk = $pk = \Concrete\Core\Permission\Key\Key::getByHandle($value);
//                if ($pk !== null) {
//                    $authorized = $pk->validate();
//                }
//            }
//            else {
                $permission = $this->getRepository()->getPermission($value);
                if (!empty($permission)) {
                    $roles = $this->getRoles();
                    foreach ($roles as $roleName) {
                        if ($permission->check($roleName)){
                            return true;
                        }
                    }
                }
            }

//        }
        return $authorized;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user->isSuperUser();
    }

    protected function loadProfile()
    {
        if (!empty($this->userInfo)) {
            $this->profile[TUser::profileKeyEmail] = $this->userInfo->getUserEmail();
        }
    }

    public function getProfileValue($key)
    {
        $result = parent::getProfileValue($key);
        if ($result === false) {
            $key = TUser::getProfileFieldKey($key);
            $result = $this->userInfo->getAttribute($key);
        }
        return empty($result) ? '' : $result;
    }

    public function setProfileValue($key, $value)
    {
        if (isset($this->userInfo)) {
            $list = self::getAttributeList();
            if (!empty($list[$key])) {
                $handle = $list[$key]['akHandle'];
                $this->userInfo->setAttribute($key,$value);
            }
        }
    }

        /**
     * @return string[]
     */
    public function getRoles()
    {
        if (!isset($this->memberGroups)) {
            $this->memberGroups = array();
            $groups = $this->user->getUserGroups();
            foreach($groups as $groupID => $groupName) {
                $group = \Concrete\Core\User\Group\Group::getByID($groupID);
                $this->memberGroups[] = $group->getGroupName();
            }
        }
        if ($this->isAuthenticated()) {
            $this->memberGroups[] = TUser::AuthenticatedRole;
        }
        return $this->memberGroups;
    }

    public function getC5User() {
        return $this->user;
    }

    public function getC5UserInfo() {
        return $this->userInfo;
    }

    private static function formatC5AttributeKey($key) {
        return TStrings::convertNameFormat($key,TStrings::keyFormat);
    }


}