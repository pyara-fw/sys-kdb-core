<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;
use SysKDB\kdm\lib\CommentUnitList;
use SysKDB\kdm\source\SourceRef;

/**
 * The AbstractCodeElement is an abstract class representing any
 * generic determined by a programming language.
 */
abstract class AbstractCodeElement extends KDMEntity
{
    /**
     * The set of code relations owned by this code model.
     *
     * @var CodeRelation
     */
    protected $codeRelation;

    /**
     * @var CommentUnitList
     */
    protected $comment;


    /**
     * Link to the physical artifact for the given code element.
     *
     * @var SourceRef
     */
    protected $source;


    /**
     * Get link to the physical artifact for the given code element.
     *
     * @return  SourceRef
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set link to the physical artifact for the given code element.
     *
     * @param  SourceRef  $source  Link to the physical artifact for the given code element.
     *
     * @return  self
     */
    public function setSource(SourceRef $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get the value of comment
     *
     * @return  CommentUnitList
     */
    public function getComment()
    {
        if (!$this->comment) {
            $this->comment = new CommentUnitList();
        }
        return $this->comment;
    }
}
