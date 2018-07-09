<?php


namespace Classes;


class SmartArray
{
    private $array;
    private $comparisonCount;
    /** @var Node */
    private $treeRoot;
    function getArray()
    {
        return $this->array;
    }

    function checkTime($methodName)
    {
        $start = microtime(true);
        call_user_func([$this, $methodName]);
        return (string)(microtime(true) - $start)." with $methodName, $this->comparisonCount comparisons</br>";
    }

    function bubbleSort()
    {
        $array =& $this->array;
        $notSorted = count($array);
        do
        {
            for ($i = 0; $i < count($array)-1; $i++)
            {
                $this->comparisonCount++;
                if ($array[$i] > $array[$i+1])
                {
                    list($array[$i], $array[$i+1]) = [$array[$i+1], $array[$i]];
                }
            }
            $notSorted --;
        }
        while($notSorted != 0);
    }

    public function insertionSort($gap = 1, &$array = [], $count = 0)
    {
        if(!$array)
        {
            $array =& $this->array;
            $count = count($array);
        }
        for ($i = 0; $i < $count; $i++)
        {
            $current = $i + $gap;
            $sortedPointer = ($current < $count) ? $i: -1;
            while ($array[$sortedPointer] > $array[$current] and $sortedPointer >= 0)
            {
                $this->comparisonCount++;
                list ($array[$sortedPointer], $array[$current]) = [$array[$current], $array[$sortedPointer]];
                $sortedPointer -= $gap;
                $current -= $gap;
            }
        }
    }

    public function shellSort()
    {
        $count = count($this->array);
        $gaps = [1750, 701, 301, 132, 57, 23, 10, 4, 1];
        foreach ($gaps as $gap)
        {
            if ($count > $gap)
            {
                $this->insertionSort($gap, $this->array, $count);
            }
        }
    }

    public function heapSort()
    {
        $array =& $this->array;
        $heapSize = count($array);
        for ($node = (int)($heapSize / 2); $node >= 0; $node --)
        {
            $this->buildHeap($node, $heapSize);
        }
        while ($heapSize > 0)
        {
            list($array[0], $array[$heapSize-1]) = [$array[$heapSize-1], $array[0]];
            $heapSize --;
            $this->buildHeap(0, $heapSize);
        }
    }

    private function buildHeap($node, $heapSize)
    {
        $array =& $this->array;
        do
        {
            $this->comparisonCount++;
            $left = $node * 2 + 1;
            $right = $node * 2 + 2;
            $largest = $node;
            $heapFault = 0;
            if ($left < $heapSize and $array[$left] > $array[$node])
            {
                $largest = $left;
                $heapFault++;
            }
            if ($right < $heapSize and $array[$right] > $array[$node])
            {
                $largest = $right;
                $heapFault++;
            }
            if ($largest != $node)
            {
                if ($heapFault == 2)
                {
                    $largest = ($array[$right] > $array[$left]) ? $right: $left;
                }
                list($array[$node], $array[$largest]) = [$array[$largest], $array[$node]];
                $node = $largest;
            }
            else
            {
                break;
            }
        }while (1);
    }

    private function renderHeap($heapSize)
    {
        $array =& $this->array;
        $currentIndex = 1;
        $this->drawLine(0);
        echo $array[0];
        $level = 1;
        while ($currentIndex < $heapSize)
        {
            $nodeCount = $level * 2;
            $this->drawLine($level);
            for ($i = $currentIndex; $i < $currentIndex + $nodeCount and $i < $heapSize; $i++)
            {
                echo $array[$i], " ~ ";
            }
            $currentIndex = $i;
            $level++;
        }
        echo "</br>============";
    }

    private function drawLine($level)
    {
        echo "</br>";
        $array =& $this->array;
        for ($i = 0; $i < count($array) - $level; $i++)
        {
            echo "-----";
        }
    }

    public function quickSort()
    {
        $high = count($this->array) - 1;
        $this->quickSortInternal($this->array, 0, $high);
    }

    private function quickSortInternal(&$array, $low, $high)
    {
        $pivot = $array[intdiv($high - $low, 2) + $low];
        $lowIndex = $low;
        $highIndex = $high;
        while($lowIndex < $highIndex)
        {
            $this->comparisonCount++;
            while ($array[$lowIndex] < $pivot)
            {
                $lowIndex++;
            }
            while ($array[$highIndex] > $pivot)
            {
                $highIndex--;
            }
            if ($highIndex > $lowIndex)
            {
                list($array[$highIndex], $array[$lowIndex]) = [$array[$lowIndex], $array[$highIndex]];
                $lowIndex++;
                $highIndex--;
            }
        };
        if ($highIndex > $low)
        {
            $this->quickSortInternal($array, $low, $highIndex - 1);
        }
        if($lowIndex < $high)
        {
            $this->quickSortInternal($array, $lowIndex + 1, $high);
        }
    }

    public function mergeSort()
    {
        $this->array = $this->mergeSortInternal($this->array);
    }

    private function mergeSortInternal($array)
    {
        $count = count($array);
        if ($count > 1)
        {
            $result = [];
            $array = array_chunk($array, $count / 2 + $count % 2);
            $left = $this->mergeSortInternal($array[0]);
            $right = $this->mergeSortInternal($array[1]);
            do
            {
                $this->comparisonCount ++;
                if ($left[0] < $right[0])
                {
                    $result[] = $left[0];
                    array_shift($left);
                }
                else
                {
                    $result[] = $right[0];
                    array_shift($right);
                }
            } while($left and $right);
            $result = array_merge($result, $left, $right);
            return $result;
        }
        return $array;
    }

    public function treeSort()
    {
        $array =& $this->array;
        $this->treeRoot = new Node($array[0]);
        for($i = 1; $i < count($array); $i++)
        {
            $this->treeRoot->addChild(new Node($array[$i]), $this->comparisonCount);
        }
        $array = [];
        $this->treeRoot->fillArray($array, $this->comparisonCount);
    }

    function __construct($count)
    {
        $this->comparisonCount = 0;
        for ($index=0; $index<$count; $index++)
        {
            $this->array[] = random_int(0, 100);
        }
    }

    function __toString()
    {
        $result = "";
        foreach ($this->array as $element)
        {
            $result .= "$element - ";
        }
        $result .= "</br> ____________________ </br>";
        return $result;
    }
}