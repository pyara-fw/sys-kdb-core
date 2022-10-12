<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\core\KDMEntity;

/**
 * Undocumented class
 */
class KDMEntityList extends ListBase
{
    /**
     *
     *
     * @param KDMEntity $entity
     * @return void
     */
    public function add(KDMEntity $entity)
    {
        if (!$this->checkMapIfExists($entity->getOid())) {
            array_push($this->list, $entity);
        }
    }

    /**
     * @param integer $i
     * @return KDMEntity|null
     */
    public function get(int $i): ?KDMEntity
    {
        return  $this->list[$i] ?? null;
    }
}
