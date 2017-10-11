<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 10/1/2017
 * Time: 4:01 PM
 */

namespace PeanutTest\scripts;


use Tops\concrete5\TConcrete5User;
use Tops\sys\TUser;

class UserTest extends TestScript
{

    public function execute()
    {
        print "Testing testuser\n";
        /**
         * @var $user TConcrete5User
         */
        $user = TUser::getByUserName('testuser');
        // $attributes = TConcrete5User::getAttributeList();
        $actual = $user->getUserName();
        $this->assertNotNull($actual,'user name');
        print "User name: $actual\n";
        $actual = $user->getFullName();
        $this->assertNotNull($actual,'full name');
        print "Full name: $actual\n";

        $actual = $user->getShortName();
        $this->assertNotNull($actual,'short name');
        print "Short name: $actual\n";

        $actual = $user->getDisplayName();
        $this->assertNotNull($actual,'display name');
        print "Display name: $actual\n";

        $actual = $user->getEmail();
        print "Email: $actual\n";
        $this->assertNotEmpty($actual,'Email');

        $actual = $user->isMemberOf(TUser::mailAdminRoleName);
        print "Mail admin? ".($actual ? 'Yes' : 'No')."\n";
        $this->assert($actual,'Not member of mail admin');

        $actual = $user->isAuthorized(TUser::appAdminPermissionName);
        $this->assert(!$actual,'is peanut admin');
        print "Peanut admin? ".($actual ? 'Yes' : 'No')."\n";

        $authenticated = $user->isAuthenticated();
        // $this->assert($authenticated,'not authenticated');
        print "Authenticated? ".($authenticated ? 'Yes' : 'No')."\n";
        $actual = $user->isAdmin();
        $this->assert(!$actual,'is admin');
        print "Admin? ".($actual ? 'Yes' : 'No')."\n";
        $actual = $user->isCurrent();
        print "Test user is current user? ".($actual ? 'Yes' : 'No')."\n";
        // $this->assert(!$actual,'not current');

        print "\nTesting Current user\n";
        $user = TUser::getCurrent();
        print "Loaded user ".$user->getUserName()."\n";
        $actual = $user->isCurrent();
        print "Is current user? ".($actual ? 'Yes' : 'No')."\n";
        $this->assert($actual,'not current');

        $actual = $user->getDisplayName();
        $this->assertNotNull($actual,'display name');
        print "Display name: $actual\n";


        $canView = $user->isAuthorized(TUser::viewDirectoryPermissionName);
        print "User ".($canView? 'can' : 'CANNOT')." view directory \n";

        $this->assertEquals($authenticated,$canView,'Should not view directory');

    }
}