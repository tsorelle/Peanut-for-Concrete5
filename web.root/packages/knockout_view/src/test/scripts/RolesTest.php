<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/17/2017
 * Time: 9:06 PM
 */

namespace PeanutTest\scripts;


use Concrete\Core\User\Group\GroupList;
use Tops\concrete5\Concrete5PermissionsManager;

class RolesTest extends TestScript
{

    public function execute()
    {
        $manager = new Concrete5PermissionsManager();
        $list = new GroupList();;
        $list->includeAllGroups();
        $collection = $list->getResults();
        foreach ($list->getResults() as $group) {
             var_dump($group);
             print "\n";
            /*
            if (strpos(strtolower($group->gName),'test role') !== false) {
                $manager->removeRole($group->gName);
                print "Deleting role $group->gName\n";
            }
            */
        }
    }
}