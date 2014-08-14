<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 10/08/14
 * Time: 19:55
 */

namespace JamesMannion\ForumBundle\Service;


abstract class BaseService {

    protected $dao;

    public function createEntity($entityToCreate)
    {
        return $this->dao->createEntity();
    }

} 