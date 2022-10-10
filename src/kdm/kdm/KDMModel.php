<?php

namespace SysKDB\kdm\kdm;

use SysKDB\kdm\lib\OwnershipEntityTrait;

/**
 * A KDMModel is an abstract class that defines common properties of
 * KDM model instances which are collections of facts about a given
 * software system from the same architectural viewpoint of one of the KDM
 * domains. KDM defines several concrete subclasses of the KDMModel class,
 * each of which defines a particular kind of a KDM model.
 */
abstract class KDMModel extends KDMFramework
{
    use OwnershipEntityTrait;
}
