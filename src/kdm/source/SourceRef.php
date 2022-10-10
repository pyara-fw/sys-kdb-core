<?php

namespace  SysKDB\kdm\source;

use SysKDB\kdm\core\Element;
use SysKDB\kdm\lib\SourceRegionList;

/**
 * The SourceRef class represents a traceability link between a particular
 * model element and the corresponding source code.
 */
class SourceRef extends Element
{
    /**
     * Optional attribute.
     * Indicates the source language of the snippet attribute.
     *
     * @var string
     */
    protected $language;

    /**
     * Optional attribute.
     * The source snippet for the given KDM element.
     * The snippet may have some internal structure, for example XML
     * tags corresponding to an abstract syntax tree of the code fragment.
     *
     * @var string
     */
    protected $snippet;

    /**
     * @var SourceRegionList
     */
    protected $sourceRegions;

    /**
     * Get indicates the source language of the snippet attribute.
     *
     * @return  string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set indicates the source language of the snippet attribute.
     *
     * @param  string  $language  Indicates the source language of the snippet attribute.
     *
     * @return  self
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get tags corresponding to an abstract syntax tree of the code fragment.
     *
     * @return  string
     */
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * Set tags corresponding to an abstract syntax tree of the code fragment.
     *
     * @param  string  $snippet  tags corresponding to an abstract syntax tree of the code fragment.
     *
     * @return  self
     */
    public function setSnippet(string $snippet)
    {
        $this->snippet = $snippet;

        return $this;
    }

    /**
     * Get the value of sourceRegions
     *
     * @return  SourceRegionList
     */
    public function getSourceRegions()
    {
        if (!$this->sourceRegions) {
            $this->sourceRegions = new SourceRegionList();
        }
        return $this->sourceRegions;
    }
}
