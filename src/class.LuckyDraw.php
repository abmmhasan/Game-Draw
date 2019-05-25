<?php

/**
 * The Lucky-Draw class takes an example array and generates Item and Item count for winners.
 *
 * Usage examples can be found in the included README file, and all methods
 * should have adequate documentation to get you started.
 *
 *
 * Minimum requirements: PHP 7.1.x, BCMath extension (may need to install manually in Linux servers).
 *
 * @author      A. B. M. Mahmudul Hasan <abmmhasan@gmail.com>
 * @copyright   Copyright (c), 2019 A. B. M. Mahmudul Hasan
 * @license     MIT public license
 */

class LuckyDraw
{
    /**
     * @param array $items , the list of items
     * @param bool $fraction , If the chances/amounts include fraction number(true by default) or not(false)
     * @param bool $check , If the items are already checked before can omit by passing false
     * @return array with Item Code/Name and Item Counter
     * @exception If required keys not present/values for the keys are not properly formatted
     */
    private $items = [];
    private $fraction;
    private $check;
    private $draw = [];

    public function __construct(array $items, bool $fraction = true, bool $check = true)
    {
        if (count($items) < 1) {
            throw new \LengthException('Invalid number of items!');
        }
        if ($check) {
            foreach ($items as $item) {
                if (!isset($item['item']) || !isset($item['chances']) || !isset($item['amounts'])) {
                    throw new \InvalidArgumentException('Required keys(item,chances,amounts) not present with all items!');
                } elseif (!is_numeric($item['chances'])) {
                    throw new \UnexpectedValueException('Chances should be a positive number(integer/float)!');
                } elseif (!is_array($item['amounts'])) {
                    throw new \UnexpectedValueException('Amounts should be a formatted array!');
                }
            }
        }
        $this->check = $check;
        $this->fraction = $fraction;
        $this->gift($items);
    }

    public function draw()
    {
        return $this->draw;
    }

    private function multiply()
    {
        if (!$this->fraction) return;
        if (($length = $this->setFraction()) === 0) return;
        $this->items = array_combine(
            array_keys($this->items),
            array_map('bcmul', $this->items,
                array_fill(0, count($this->items),
                    str_pad(1, $length, '0'))));
    }

    private function setFraction()
    {
        $length = 0;
        foreach ($this->items as $item) {
            if ((int)$item != $item) {
                $fraction = strlen(explode(".", $item)[1]) + 1;
                if ($fraction > $length) {
                    $length = $fraction;
                }
            }
        }
        return (int)$length;
    }

    private function getPositive()
    {
        $this->items = array_filter($this->items, function ($v) {
            return $v > 0;
        });
    }

    private function numSequence($array)
    {
        if (!array_key_exists(0, $array)) return false;
        return array_keys($array) === range(0, count($array) - 1);
    }

    private function gift($items)
    {
        $this->items = array_column($items, 'chances', 'item');
        $item = $this->generate();
        $amounts = $items[array_search($item, array_column($items, 'item'))]['amounts'];
        if (count($amounts) == 1) {
            $count = current($amounts);
        } elseif ($this->numSequence($amounts)) {
            $count = $amounts[rand(0, count($amounts) - 1)];
        } else {
            $this->items = $amounts;
            $count = $this->generate();
        }
        $this->draw = [$item, $count];
    }

    private function generate()
    {
        if (count($this->items) == 1) return current($this->items);
        $this->getPositive();
        $this->multiply();
        $sum = array_sum($this->items);
        if ($sum > mt_getrandmax() || $sum < 1) {
            if ($this->check) throw new \UnexpectedValueException('Chances(Item/Amount) out of range!');
            return;
        }
        $rand = mt_rand(1, (int)$sum);
        foreach ($this->items as $key => $value) {
            $rand -= (int)$value;
            if ($rand <= 0) {
                return $key;
            }
        }
        return array_search(max($this->items), $this->items);
    }
}
