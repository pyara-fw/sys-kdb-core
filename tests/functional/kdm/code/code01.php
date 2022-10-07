<?php

namespace code\lib;

use parts\Engine;
use parts\Tire as Wheel;

class Car
{
    private $myPrivate = '1';
    protected $myProtected = 2;
    public $myPublic;
    public $myVar;

    public const SOMETHING = 1;

    public function init()
    {
        $this->addPart(new Engine());

        // $x = new \x\code\ClassName1();
        // $y = \code\ClassName2::functionName();
        // $z = new nextPackage\ClassName3();

        // $myStr = "Some literal";
    }

    /**
     * List of parts linked to this car
     *
     * @var array
     */
    protected $parts = [];

    /**
     * Vehicle Identification Number
     *
     * @var string
     */
    protected $vin;


    /**
     * Get the value of vin
     * @return string
     */
    public function getVin(): string
    {
        return $this->vin;
    }

    /**
     * Set the value of vin
     * @param string $vin
     * @return  self
     */
    public function setVin(string $vin)
    {
        $this->vin = $vin;

        return $this;
    }

    /**
     * Get the value of parts
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Set the value of parts
     *
     * @return  self
     */
    public function setParts($parts)
    {
        $this->parts = $parts;

        return $this;
    }

    protected function addPart($part)
    {
        $this->parts[] = $part;
    }
}
