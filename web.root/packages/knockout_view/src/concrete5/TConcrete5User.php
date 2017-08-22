<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 3/30/2017
 * Time: 7:17 AM
 */

// todo Complete and test user implementation

namespace Tops\concrete5;


use Concrete\Core\User\UserInfoRepository;
use Tops\sys\IUser;
use Tops\sys\TAbstractUser;
use Tops\sys\TConfiguration;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;
use Core;

/**
 * Class TConcrete5User
 * @package Tops\sys
 *
 * see https://documentation.concrete5.org/developers/users-groups/reading-data-existing-users
 */
class TConcrete5User extends TAbstractUser
{

    /**
     * @var $user User
     */
    private $user;

    /**
     * @var $userInfo UserInfo
     */
    private $userInfo;

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

    private $memberGroups = false;

    private function getUserInfo() {
        if (empty($this->user)) {
            return false;
        }
        if (empty ($this->userInfo)) {
            $id = $this->user->getUserID();
            // $this->userInfo = UserInfo::getByID($id);
            $this->userInfo = $this->getUserInfoRepository()->getByID($id);
        }
        return $this->userInfo;
    }

    private function setUser($user) {
        $this->user = $user;
        $this->userInfo = null;
        $this->memberGroups = false;
    }

    private function getMemberGroups()
    {
        if ($this->memberGroups === false) {
            $this->memberGroups = array();
            $groups = $this->user->getUserGroups();
            foreach($groups as $groupID => $groupName) {
                $group = \Concrete\Core\User\Group\Group::getByID($groupID);
                $this->memberGroups[] = strtolower($group->getGroupName());
            }
        }
        return $this->memberGroups;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function loadById($id)
    {
        $this->setUser(User::getByUserID($id));
    }

    /**
     * @param $userName
     * @return mixed
     */
    public function loadByUserName($userName)
    {
        $this->userInfo = $this->getUserInfoRepository()->getByName($userName);
        // $this->userInfo = Core::make('Concrete\Core\User\UserInfoRepository')->getByName($userName);
        $this->user = User::getByUserID($this->userInfo->getUserID());
    }

    /**
     * @return mixed
     */
    public function loadCurrentUser()
    {
        $this->setUser(new User());
    }

    /**
     * @param $roleName
     * @return bool
     */
    public function isMemberOf($roleName)
    {
        $groups = $this->getMemberGroups();
        $roleName = strtolower($roleName);
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
        return $this->user->isRegistered();
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isAuthorized($value = '')
    {
        if ($this->user->isSuperUser()) {
            return true;
        }

        // treat group membership as a permission
        if ($this->isMemberOf($value)) {
            return true;
        }
        // todo: check custom permissions
        // might need custom table for this
        // see  https://documentation.concrete5.org/developers/permissions-access-security/creating-custom-permissions
        return false;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        // TODO: Implement getFirstName() method.
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        // TODO: Implement getLastName() method.
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        // TODO: Implement getUserName() method.
    }

    /**
     * @param bool $defaultToUsername
     * @return string
     */
    public function getFullName($defaultToUsername = true)
    {
        // TODO: Implement getFullName() method.
    }

    /**
     * @param bool $defaultToUsername
     * @return string
     */
    public function getUserShortName($defaultToUsername = true)
    {
        // TODO: Implement getUserShortName() method.
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        $info = $this->getUserInfo();
        if (empty($info)) {
            return '';
        }
        return $info->getUserEmail();
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user->isSuperUser();
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        return $this->user->isRegistered();
    }

    public function getProfileValue($key)
    {
        // TODO: Implement getProfileValue() method.
    }

    public function setProfileValue($key, $value)
    {
        // TODO: Implement setProfileValue() method.
    }

    protected function test()
    {
        // TODO: Implement test() method.
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    protected function loadProfile()
    {
        // TODO: Implement loadProfile() method.
    }

    /**
     * @param $email
     * @return mixed
     */
    public function loadByEmail($email)
    {
        // TODO: Implement loadByEmail() method.
        throw new \Exception("Method 'loadByEmail' not implemented");
    }
}