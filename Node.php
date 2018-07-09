<?php


namespace Classes;


class Node
{
    private $value;
    /**@var Node*/
    private $leftChild;
    /**@var Node*/
    private $rightChild;

    public function addChild(Node $child, &$comparisons)
    {
        $comparisons++;
        if ($child->value > $this->value)
        {
            if($this->rightChild)
            {
                $this->rightChild->addChild($child, $comparisons);
            }
            else
            {
                $this->rightChild = $child;
            }
        }
        else
        {
            if($this->leftChild)
            {
                $this->leftChild->addChild($child, $comparisons);
            }
            else
            {
                $this->leftChild = $child;
            }
        }
    }
    public function fillArray(&$array, &$comparisons)
    {
        $comparisons++;
        if($this->leftChild)
        {
            $this->leftChild->fillArray($array, $comparisons);
        }
        else
        {
            $array[] = $this->value;
        }
        if ($this->rightChild)
        {
            $this->rightChild->fillArray($array, $comparisons);
        }
        else
        {
            $array[] = $this->value;
        }
    }
    public function __construct($newValue)
    {
        $this->value = $newValue;
    }
}