<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/3/2019
 * Time: 8:07 AM
 */

namespace Tops\concrete5;


use Peanut\sys\TVmContext;
use Tops\sys\TPermissionsManager;
use Tops\sys\TUser;

class Concrete5VmContext extends TVmContext
{
    /**
     * @var Concrete5Repository
     */
    private $respostory;
    private function getRepository() {
        if (!isset($this->respostory)) {
            $this->respostory = new Concrete5Repository();
        }
        return $this->respostory;
    }

    private function isInRole($role) {
        $user = TUser::getCurrent();
        if ($role == TPermissionsManager::guestRole) {
            return !$user->isAuthenticated();
        }
        return $user->isMemberOf($role);
    }

    protected function get($contextId)
    {
        $result = self::getNullContext();
        $blockData = $this->getRepository()->getKnockoutViewData($contextId);
        if (!empty($blockData)) {
            $result->viewmodel = $blockData->viewmodel;
            $parts = explode('?', $blockData->inputvalue);
            if (sizeof($parts) > 1) {
                $role = $parts[0];
                if ($this->isInRole($role)) {
                    $result->value = $parts[1];
                }
            } else {
                $result->value = $parts[0];
            }
        }
        return $result;
    }
}