<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;
use SysKDB\kdm\lib\AbstractCodeRelationshipList;
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
     * @var AbstractCodeRelationshipList
     */
    protected $codeRelation;

    /**
     * @var CommentUnitList
     */
    protected $comment;


    /**
     * @var CodeModel
     */
    protected $model;

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

    /**
     * Get the value of model
     *
     * @return  CodeModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the value of model
     *
     * @param  CodeModel  $model
     *
     * @return  self
     */
    public function setModel(CodeModel $model)
    {
        $this->model = $model;
        $model->getCodeElement()->add($this);
        return $this;
    }

    /**
     * Get the set of code relations owned by this code model.
     *
     * @return  AbstractCodeRelationshipList
     */
    public function getCodeRelation()
    {
        if (!is_object($this->codeRelation)) {
            $this->codeRelation = new AbstractCodeRelationshipList();
        }
        return $this->codeRelation;
    }
}
