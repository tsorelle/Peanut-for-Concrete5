<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/14/2017
 * Time: 9:02 AM
 */

use PHPUnit\Framework\TestCase;
use Tops\concrete5\Concrete5PeanutInstaller;

class InstallerTest extends TestCase
{
    public function testGetInstallerInstance() {
        $actual = \Peanut\sys\PeanutInstaller::GetInstaller();
        $this->assertNotNull($actual);
        $this->assertInstanceOf('Tops\concrete5\Concrete5PeanutInstaller',$actual);
    }

}


