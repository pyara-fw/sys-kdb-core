<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\ModelElement;
use SysKDB\kdm\lib\OwnershipEntityTrait;

/**
 * The CommentUnit is a meta-model element that represents comments in
 * existing systems (including any special comments). CommentUnit element
 * can be used to introduce comments during transformation of the existing
 * system (including special comments).
 */
class CommentUnit extends ModelElement
{
    use OwnershipEntityTrait;

    /**
     * the representation of the comment
     *
     * @var string
     */
    protected $text;



    /**
     * Get the representation of the comment
     *
     * @return  string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the representation of the comment
     *
     * @param  string  $text  the representation of the comment
     *
     * @return  self
     */
    public function setText(string $text)
    {
        $this->text = $text;

        return $this;
    }
}
