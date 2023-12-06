<?php

namespace AbmmHasan\Draw;

use Exception;
use InvalidArgumentException;
use LengthException;
use UnexpectedValueException;

class LuckyDraw
{

    public function __construct(private array $items)
    {
    }

    /**
     * Checks the validity of the items array.
     *
     * @throws LengthException if the number of items is less than 1.
     * @throws InvalidArgumentException if required keys (item, chances, amounts) are not present for any item.
     */
    private function check(): void
    {
        if (count($this->items) < 1) {
            throw new LengthException('Invalid number of items!');
        }
        foreach ($this->items as $item) {
            if (!isset($item['item']) || !isset($item['chances']) || !isset($item['amounts'])) {
                throw new InvalidArgumentException('Required keys(item,chances,amounts) not present with all items!');
            }
        }
    }

    /**
     * Picks an item & amount from the list based on chances.
     *
     * @param bool $check Indicates whether to run the check method before picking an item (default: true)
     * @return array Returns an array containing the picked item and its amount
     * @throws Exception
     */
    public function pick(bool $check = true): array
    {
        $check && $this->check();
        $items = array_column($this->items, 'chances', 'item');
        $items = $this->prepare($items);
        $pickedItem = $this->draw($items);
        $amounts = $this->items[array_search($pickedItem, array_column($this->items, 'item'))]['amounts'];
        is_string($amounts) && $amounts = [$this->weightedAmountRange($amounts)];
        return [
            'item' => $pickedItem,
            'amount' => match (true) {
                count($amounts) === 1 => current($amounts),
                $this->isSequential($amounts) => $amounts[array_rand($amounts)],
                default => $this->draw($amounts)
            }
        ];
    }

    /**
     * Generates a weighted random number within a specified range.
     *
     * @param string $amounts A string containing the minimum, maximum, and bias values separated by commas.
     * @return float|int A single randomly generated number within the specified range.
     * @throws UnexpectedValueException If the amount range is invalid or the bias is less than or equal to 0.
     */
    private function weightedAmountRange(string $amounts): float|int
    {
        $amounts = str_getcsv($amounts);
        count($amounts) !== 3 && throw new UnexpectedValueException('Invalid amount range (expected: min,max,bias).');
        [$min, $max, $bias] = $amounts;
        $max <= $min && throw new UnexpectedValueException('Maximum value should be greater than minimum.');
        $bias <= 0 && throw new UnexpectedValueException('Bias should be greater than 0.');
        $min = floatval($min);
        $max = floatval($max);
        $bias = floatval($bias);
        $selected = min(
            round($min + pow(lcg_value(), $bias) * ($max - $min + 1), $this->getFractionLength([$min, $max])),
            $max
        );
        return max($selected, $min);
    }

    /**
     * Draws among an array of items based on given weight.
     *
     * @param array $items The array of items to be processed.
     * @return string The selected item from the array.
     * @throws Exception if the random number generation fails.
     */
    private function draw(array $items): string
    {
        if (count($items) === 1) {
            return key($items);
        }

        $random = random_int(1, array_sum($items));
        foreach ($items as $key => $value) {
            $random -= (int)$value;
            if ($random <= 0) {
                return $key;
            }
        }
        return array_search(max($items), $items);
    }

    /**
     * Prepares an array of items.
     *
     * @param array $items The array of items to be prepared.
     * @return array The prepared array of items.
     * @throws UnexpectedValueException
     */
    private function prepare(array $items): array
    {
        if ($length = $this->getFractionLength($items)) {
            $items = $this->multiply($items, $length);
        }
        return $items;
    }

    /**
     * Calculate the length of the fraction part in an array of items.
     *
     * @param array $items The array of items to calculate the fraction length from.
     * @return int The length of the fraction part.
     * @throws UnexpectedValueException
     */
    private function getFractionLength(array $items): int
    {
        $length = 0;
        foreach ($items as $item) {
            $item > 0 || throw new UnexpectedValueException('Chances should be positive decimal number!');
            $fraction = strpos($item, '.');
            $fraction && $length = max(strlen($item) - $fraction - 1, $length);
        }
        return (int)$length;
    }

    /**
     * Multiplies each item in the given array by a specified decimal length.
     *
     * @param array $items The array of items to be multiplied.
     * @param int $length The length to multiply each item by.
     * @return array The resulting array with each item multiplied by the specified length.
     */
    private function multiply(array $items, int $length): array
    {
        $padding = '1' . str_repeat('0', $length);
        return array_map(function ($value) use ($padding) {
            return bcmul($value, $padding);
        }, $items);
    }

    /**
     * Determines if an array is sequential.
     *
     * @param array $array The array to check.
     * @return bool Returns true if the array is sequential, false otherwise.
     */
    private function isSequential(array $array): bool
    {
        if (!array_key_exists(0, $array)) {
            return false;
        }
        $keys = array_keys($array);
        return $keys === array_keys($keys);
    }
}
