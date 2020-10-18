<?php

/**
 * @link https://github.com/abmmhasan/Mega-Draw
 */

namespace AbmmHasan\Draw;

/**
 * Mega-Draw class takes 2 example array and generates winners for each prize according to given amount per prize.
 *
 * Usage examples can be found in the included README file, and all methods
 * should have adequate documentation to get you started.
 *
 *
 * Minimum requirements: PHP 7.x.
 *
 * @author      A. B. M. Mahmudul Hasan <abmmhasan@gmail.com>
 * @copyright   Copyright (c), 2019 A. B. M. Mahmudul Hasan
 * @license     MIT public license
 */
class MegaDraw
{
    /**
     * @param array $items the list of items
     * @param array $users
     * @param bool $grouped
     * @return array with Item Code/Name and Item Counter
     * @exception If required keys not present/values for the keys are not properly formatted
     */
    public function get(array $items, array $users, $grouped = false)
    {
        if (count($items) < 1) {
            throw new \LengthException('Invalid number of items!');
        }
        if (!self::intValue($items) || !self::positiveValue($items)) {
            throw new \UnexpectedValueException('Prize quantity should be a positive integer value!');
        }
        if ($grouped) return self::groupedGift($items, $users);
        else return self::generalGift($items, $users);
    }

    private static function intValue(array $array)
    {
        return $array === array_filter($array, 'is_int');
    }

    private static function positiveValue(array $array)
    {
        return min($array) >= 0;
    }

    private static function generalGift($items, $users)
    {
        $users = self::select($users, array_sum($items));
        $select = array();
        foreach ($items as $k => $v) {
            $select[$k] = array_splice($users, 0, $v);
        }
        return $select;
    }

    private static function groupedGift($items, $users)
    {
        $users = self::groupBase($users);
        $gift_array = [];
        foreach ($users as $gift => $userGroup) {
            $gift_array[$gift] = self::select($userGroup, $items[$gift]);
        }
        return $gift_array;
    }

    private static function groupBase($users)
    {
        $group = array();
        foreach ($users as $user => $gift) {
            $group[$gift][] = $user;
        }
        return $group;
    }

    private static function select($users, $total)
    {
        $select = array();
        for ($i = 0; $i < $total; $i++) {
            $offset = rand(0, count($users) - 1);
            if (!isset($users[$offset])) {
                break;
            }
            $select[] = $users[$offset];
            array_splice($users, $offset, 1);
        }
        return $select;
    }
}
