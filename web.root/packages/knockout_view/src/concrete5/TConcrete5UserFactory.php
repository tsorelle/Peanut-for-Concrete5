<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 3/30/2017
 * Time: 7:15 AM
 */

namespace Tops\concrete5;



use Tops\sys\IUserFactory;

class TConcrete5UserFactory implements IUserFactory
{

    /**
     * @return /Tops/sys/IUser
     */
    public function createUser()
    {
        return new TConcrete5User();
    }
}