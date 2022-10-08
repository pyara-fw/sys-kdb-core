<?php

namespace  SysKDB\kdm\source;

/**
 * The SourceFile class represents source files.
 * This meta-model element is the key part of the traceability
 * mechanism of KDM whose purpose is to provide links between
 * code elements and their physical implementations using the
 * SourceRegion mechanism from the Source package.
 *
 */
class SourceFile extends InventoryItem
{
    /**
     *
     *
     * @var string
     */
    protected $language;

    /**
     *
     *
     * @var string
     */
    protected $encoding;

    /**
     * Get the value of language
     *
     * @return  string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set the value of language
     *
     * @param  string  $language
     *
     * @return  self
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get the value of encoding
     *
     * @return  string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set the value of encoding
     *
     * @param  string  $encoding
     *
     * @return  self
     */
    public function setEncoding(string $encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }
}
