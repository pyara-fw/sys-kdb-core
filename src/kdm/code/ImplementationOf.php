<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;

/**
 * The ImplementationOf is a meta-model element that represents “implementation”
 * association between a CodeItem; for example, a MethodUnit and a particular
 * “external” entity; for example, a MethodUnit owned by an InterfaceUnit.
 * “ImplementationOf” relationship represents associations between a declaration
 * and a definition of a computation object, common to various programming languages.
 * While the “Implements” relationship is between entire containers (the target is an
 * InterfaceUnit), the “ImplementationOf” relationship represents a broader range of
 * situations:
 *
 * - Particular MethodUnit of a ClassUnit that “Implements” an InterfaceUnit, is an
 *   “ImplementationOf” a particular MethodUnit, owned by that InterfaceUnit.
 *
 * - A CallableUnit may be an “ImplementationOf” a CallableUnit with kind external,
 *   which represents the declaration (the prototype) of that CallableUnit.
 *
 * - A StorableUnit may be an “ImplementationOf” a StorableUnit with kind external,
 *   which represents the external declaration of the StorableUnit, such as, for example,
 *   the “extern” construct in the C language.
 *
 */
class ImplementationOf extends AbstractCodeRelationship
{
    /**
     * CodeItem that implements a certain “declaration.”
     *
     * @var CodeItem
     */
    protected $from;

    /**
     * “declaration” that is being implemented by the CodeItem.
     *
     * @var CodeItem
     */
    protected $to;


    /**
     * @return  CodeItem
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param  CodeItem  $from
     *
     * @return  self
     */
    public function setFrom(KDMEntity $from)
    {
        if (is_a($from, CodeItem::class)) {
            $this->from = $from;
        }

        return $this;
    }

    /**
     * @return  CodeItem
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  CodeItem  $to  actual parameter to template instantiation
     *
     * @return  self
     */
    public function setTo(KDMEntity $to)
    {
        if (is_a($to, CodeItem::class)) {
            $this->to = $to;
        }

        return $this;
    }
}
