<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 23/01/2018
 * Time: 20:10
 */

namespace Portal\Model\DAL;

use Portal\Util\DBLayer;
use Portal\Util\Utils;



class MenuDAL
{
    private $db = null;

    function __construct()
    {
        $this->db = DBLayer::Connect();
    }

    public function getHierarchyForUser()
    {

    }



}