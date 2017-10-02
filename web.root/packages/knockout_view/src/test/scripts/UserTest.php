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
        /**
         * @var $user TConcrete5User
         */
        $user = TUser::getByUserName('testuser');
        $attributes = TConcrete5User::getAttributeList();
        $actual = $user->getUserName();
        $this->assertNotNull($actual,'user name');
        print "User name: $actual\n";
        $actual = $user->getFullName();
        $this->assertNotNull($actual,'full name');
        print "Full name: $actual\n";
        $actual = $user->getUserShortName();
        $this->assertNotNull($actual,'short name');
        print "Short name: $actual\n";
        $actual = $user->getEmail();
        $this->assertNotNull($actual,'Email');
        print "Email: $actual\n";
        $actusl = $user->isMemberOf('mail_administrator');
        print "Mail admin? ".($actual ? 'Yes' : 'No')."\n";
        $this->assert($actual,'Not member of mail admin');
        $actual = $user->isAuthorized('update_directory_members');
        $this->assert($actual,'cannot update');
        print "Can update? ".($actual ? 'Yes' : 'No')."\n";
        $this->assert($user->isAuthenticated(),'not authenticated');
        print "Authenticated? ".($actual ? 'Yes' : 'No')."\n";
        $actual = $user->isAdmin();
        $this->assert(!$actual,'is admin');
        print "Admin? ".($actual ? 'Yes' : 'No')."\n";
        $actual = $user->isCurrent();
        print "Test user is current user? ".($actual ? 'Yes' : 'No')."\n";
        $this->assert(!$actual,'not current');

        $user = TUser::getCurrent();
        print "Loaded user ".$user->getUserName()."\n";
        $actual = $user->isCurrent();
        print "Is current user? ".($actual ? 'Yes' : 'No')."\n";
        $this->assert($actual,'not current');





    }
}