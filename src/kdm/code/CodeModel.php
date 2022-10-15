<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\kdm\KDMModel;
use SysKDB\kdm\lib\AbstractCodeElementList;

/**
 * The CodeModel is the specific KDM model that owns collections of
 * facts about the existing software system such that these facts
 * correspond to the Code domain. CodeModel is the only model of the
 * Program Elements Layer of KDM.
 */
class CodeModel extends KDMModel
{
    /**
     *
     *
     * @var AbstractCodeElementList
     */
    protected $codeElement;

    /**
     * Get the value of codeElement
     *
     * @return  AbstractCodeElementList
     */
    public function getCodeElement()
    {
        if (!is_object($this->codeElement)) {
            $this->codeElement = new AbstractCodeElementList();
        }
        return $this->codeElement;
    }
}
