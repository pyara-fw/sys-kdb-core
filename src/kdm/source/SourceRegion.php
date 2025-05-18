<?php

namespace  SysKDB\kdm\source;

use SysKDB\kdm\core\Element;

/**
 * The SourceRegion class provides a pointer to a single region of source.
 * The SourceRegion element provides the capability to precisely map model
 * elements to a particular region of source that is not necessarily text.
 * The nature of the source code within the physical artifact is indicated
 * by the language attribute of the SourceRegion element or the language
 * attribute of the SourceFile element. The language attribute of the
 * SourceRegion element overrides that of the SourceFile element if
 * both are present.
 */
class SourceRegion extends Element
{
    /**
     * The line number of the first character of the source region.
     *
     * @var int
     */
    protected $startLine;

    /**
     * Provides the position of the first character of the source region.
     *
     * @var int
     */
    protected $startPosition;

    /**
     * The line number of the last character of the source region.
     *
     * @var int
     */
    protected $endLine;

    /**
     * The position of the last character of the source region.
     *
     * @var int
     */
    protected $endPosition;


    /**
     * Optional attribute.
     * The language indicator of the source code for the given source region.
     *
     * @var string
     */
    protected $language;

    /**
     * Optional attribute.
     * The location of the physical artifact that contains the given source region.
     *
     * @var string
     */
    protected $path;

    /**
     * This association allows zero or more SourceRegion elements to be associated
     * with a single SourceFile element of the Inventory Model.
     * Cardinality: [0:1]
     *
     * @var SourceFile
     */
    protected $file;


    /**
     * @var SourceRef
     */
    protected $sourceRef;

    /**
     * Get the line number of the first character of the source region.
     *
     * @return  int
     */
    public function getStartLine()
    {
        return $this->startLine;
    }

    /**
     * Set the line number of the first character of the source region.
     *
     * @param  int  $startLine  The line number of the first character of the source region.
     *
     * @return  self
     */
    public function setStartLine(int $startLine)
    {
        $this->startLine = $startLine;

        return $this;
    }

    /**
     * Get provides the position of the first character of the source region.
     *
     * @return  int
     */
    public function getStartPosition()
    {
        return $this->startPosition;
    }

    /**
     * Set provides the position of the first character of the source region.
     *
     * @param  int  $startPosition  Provides the position of the first character of the source region.
     *
     * @return  self
     */
    public function setStartPosition(int $startPosition)
    {
        $this->startPosition = $startPosition;

        return $this;
    }

    /**
     * Get the line number of the last character of the source region.
     *
     * @return  int
     */
    public function getEndLine()
    {
        return $this->endLine;
    }

    /**
     * Set the line number of the last character of the source region.
     *
     * @param  int  $endLine  The line number of the last character of the source region.
     *
     * @return  self
     */
    public function setEndLine(int $endLine)
    {
        $this->endLine = $endLine;

        return $this;
    }

    /**
     * Get the position of the last character of the source region.
     *
     * @return  int
     */
    public function getEndPosition()
    {
        return $this->endPosition;
    }

    /**
     * Set the position of the last character of the source region.
     *
     * @param  int  $endPosition  The position of the last character of the source region.
     *
     * @return  self
     */
    public function setEndPosition(int $endPosition)
    {
        $this->endPosition = $endPosition;

        return $this;
    }

    /**
     * Get the language indicator of the source code for the given source region.
     *
     * @return  string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set the language indicator of the source code for the given source region.
     *
     * @param  string  $language  The language indicator of the source code for the given source region.
     *
     * @return  self
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get the location of the physical artifact that contains the given source region.
     *
     * @return  string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the location of the physical artifact that contains the given source region.
     *
     * @param  string  $path  The location of the physical artifact that contains the given source region.
     *
     * @return  self
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the file (SourceFile)
     *
     * @return  SourceFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the file (SourceFile)
     *
     * @param  SourceFile  $file
     *
     * @return  self
     */
    public function setFile(SourceFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the value of sourceRef
     *
     * @return  SourceRef
     */
    public function getSourceRef()
    {
        return $this->sourceRef;
    }

    /**
     * Set the value of sourceRef
     *
     * @param  SourceRef  $sourceRef
     *
     * @return  self
     */
    public function setSourceRef(SourceRef $sourceRef)
    {
        $this->sourceRef = $sourceRef;

        return $this;
    }
}
