<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\kdm\KDMModel;

/**
 * The CodeModel is the specific KDM model that owns collections of
 * facts about the existing software system such that these facts
 * correspond to the Code domain. CodeModel is the only model of the
 * Program Elements Layer of KDM.
 */
class CodeModel extends KDMModel
{
    protected $codeElement;
}
