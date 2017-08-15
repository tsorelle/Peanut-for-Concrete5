<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/14/2017
 * Time: 5:31 PM
 */

namespace Tops\concrete5;


use Peanut\sys\PeanutInstaller;

class Concrete5PeanutInstaller extends PeanutInstaller
{

    public function getNativeDbConfiguration()
    {

        // TODO: Implement getNativeDbConfiguration() method.
        $result = $this->makeDbParameters('database','user','password');
        return $result;
    }
}