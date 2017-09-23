<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/17/2017
 * Time: 9:06 PM
 */

namespace PeanutTest\scripts;


use Concrete\Core\User\Group\GroupList;

class RolesTest extends TestScript
{

    public function execute()
    {
        $list = new GroupList();;
        $list->includeAllGroups();
        $collection = $list->getResults();
        foreach ($list->getResults() as $group) {
            var_dump($group);
            print "\n";
        }
    }
}