# Lucky Draw

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)


This class takes an example array (format is given in example file, will also explain below) and generates Item and Item count for winners.

> Please don't use this to generate things/prizes with People's hard earned money. It is intended to make things fun with bonus gifts only.

To draw multiple Gifts for multiple user same time use [Mega Draw Library](https://abmmhasan.github.io/Mega-Draw "Mega Draw").

## Prerequisits

Language: PHP 7+

PHP Extension: BCMath (may need to install manually in Linux servers)

## Usage

### Input Data

```php
[
    [
        'item' => 'product_000_NoLuck', // Item code or Identifier
        'chances' => '100000',          // Item Chances
        'amounts '=> [ 1 ]              // Item Amounts
    ],
    [
        'item' => 'product_001',
        'chances' => '1000',
        'amounts' => [ rand(1,100) ]    // Random Value passing
    ],
    [
        'item' => 'product_002',
        'chances' => '500.001',         // Fraction Allowed
        'amounts' => [
            1 => 100,                   // Amount chances
            5 => 50,                    // Format: Amount => Chances
            10 => 10.002,               // Fraction allowed
            rand(50,60) => 1,           // Random Value in Amount
        ]
    ],
    [
        'item' => 'product_003',
        'chances' => '100',
        'amounts' => [
            1 => 100,
            5 => 50,
            10 => 10,
            20 => 5, 
        ]
    ],
    [
        'item' => 'product_004',
        'chances' => '1',
        'amounts' => [ 10, 15, 30, 50 ] // Amounts without probability
    ],
]
```

- **item**: Provide your item's unique identifier

- **chances**: Weight of item.
    - It will be compared along all the items in array. 
    - The higher the chances the greater the chances of getting the item.
    - Fraction number supported
    - In case of active inventory we can pass available item stock here
    
- **amounts**: Array of Item amount. It can be any like following:
    - Single Value, i.e. [ 1 ] or random single value, i.e. [ 1-100 ]
    - Fraction number supported
    - Can be weighted amount, i.e.    
        ```php
        [
            5 => 100,
            15 => 50,
            50 => 10,
            80 => 5.001
        ]
        ```      
    - We can also pass random single value, i.e. [ 50-100 ] in amount part using rand() or mt_rand().       
        ```php
        [
            1 => 100,
            5  => 50,
            10 => 10,
            rand(50,100) => 5
        ]
        ```
    - Or can be selective amount for random pick
         ```php
        [ 10, 15, 30, 50, 90 ]
        ```

### Output Data

```markdown
product_000_NoLuck (1)                 // Item Code and Amount
```

```php
list( $p, $c ) = (new LuckyDraw($prizes))->draw();
```

- We will pass the Formatted Input i.e. $prizes
- From above example, (after execution) $p will be the Item Code and $c will be the item count.

## Inventory Solutions

Available stock should be passed (after subtracting used amount from stock amount) in chances properly.

## Performance

- **Single Query**
    - Execution time: 0.000036 Second (average)
    - Used Memory: 392.59 KB, Peak: 436.39 KB
- **10 Query**
    - Execution time: 0.000150 Second (average)
    - Used Memory: 393.16 KB, Peak: 435.95 KB
- **Tested on**
    - CPU: 3.7 GHz Core i3-6100
    - RAM: 12GB 2400 Bus
    - OS: Windows 10 (64 bit)
    - _Query: Above example (in Input section) has been processed for getting output_

## Support

Having trouble? Create an issue!
