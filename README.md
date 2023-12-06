# Game Draw

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/56e7f49275dc4042b67d53b4209b193d)](https://www.codacy.com/gh/abmmhasan/Game-Draw/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=abmmhasan/Game-Draw&amp;utm_campaign=Badge_Grade)
![Libraries.io dependency status for GitHub repo](https://img.shields.io/librariesio/github/abmmhasan/game-draw)
![Packagist Downloads](https://img.shields.io/packagist/dt/abmmhasan/game-draw)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
![Packagist Version](https://img.shields.io/packagist/v/abmmhasan/game-draw)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/abmmhasan/game-draw)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/abmmhasan/game-draw)


The Game Draw library provides 2 different way of winner selection based on user's input and selected method.

> Please don't use this to generate things/prizes with People's hard-earned money. It is intended to make things fun with bonus gifts only.


## Prerequisits

Language: PHP 8/+

PHP Extension: BCMath (may need to install manually)

## Installation

```
composer require abmmhasan/game-draw
```

## Usage (Lucky Draw)

### Input Data

```php
$products = [
    [
        'item' => 'product_000_NoLuck', // Item code or Identifier
        'chances' => '100000',          // Item Chances
        'amounts'=> [ 1 ]              // Item Amounts
    ],
    [
        'item' => 'product_001',
        'chances' => '1000',
        'amounts' => '1.5,10.00001,1'    // Weighted CSV formatted range (min,max,bias)
    ],
    [
        'item' => 'product_002',
        'chances' => '500.001',         // Fraction Allowed
        'amounts' => [
            1 => 100,                   // Amount chances
            5 => 50,                    // Format: Amount => Chances
            10 => 10.002,               // Fraction allowed
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

- **chances**: Weight of item (Float/Int).
    - It will be compared along all the items in array. 
    - The higher the chances the greater the chances of getting the item.
    - In case of active inventory you can pass available item stock here
    
- **amounts**: String or Array of Item amount (Float/Int). It can be any like:
    - (array) Single Positive value, i.e. [ 1 ] or Multiple Positive value (randomly picked), i.e. [ 1, 2, 3, 5]
    - (array) Weighted amount, i.e.    
        ```php
        [
            5 => 100,
            15 => 50,
            50 => 10,
            80 => 5.001
        ]
        ```
    - (String) Weighted CSV formatted range (min,max,bias) ```'1,10.00001,0.001'```
      - Only 3 members allowed in CSV format **min,max,bias**
      - Max should be greater than or equal to min, bias should be greater than 0
      - The higher the bias, the more the chance to pick the lowest amount

### Output Data

```php
$luckyDraw = new AbmmHasan\Draw\LuckyDraw($products);
$luckyDraw->pick()
```
Will output the data similar as following,

```php
[
    'item' => 'product_000_NoLuck', // The item name
    'amount' => 1 // the selected amount
]
```

### Inventory Solutions

Available stock should be passed (after subtracting used amount from stock amount) in chances properly.

## Usage (Grand Draw)

### Input Data

```php
$prizes = 
[
    'product_001'=>50,        // Item Code/Identifier => Amount of the item
    'product_002'=>5,
    'product_003'=>3,
    'product_004'=>2,
    'product_005'=>1
];
```

- **item**: Provide your item's unique identifier

- **amounts**: Amount of gift. It must be a positive integer value.

To pass users, you've to make a CSV file with at-least 1 column. 1st column will indicate user identity.

```csv
"usr47671",
"usr57665",
"usr47671",.....
```

### Output Data

```php
$bucket = new GrandDraw();

// set resources
$bucket->setItems([ // set prizes
    'product_001' => 10,        // Item Code/Identifier => Amount of the item
    'product_002' => 5,
    'product_003' => 3,
    'product_004' => 2,
    'product_005' => 1
])->setUserListFilePath('./Sample1000.csv'); // set the CSV file location

// get the winners
$bucket->getWinners()
```
Will provide the output similar as following,
```php
Array
(
    [product_001] => Array
        (
            [0] => usr47671
            [1] => usr57665
            [2] => usr92400
            [3] => usr7249
            [4] => usr37860
            [5] => usr57280
            [6] => usr97204
            [7] => usr82268
            [8] => usr16521
            [9] => usr24864
        )

    [product_002] => Array
        (
            [0] => usr50344
            [1] => usr60450
            [2] => usr62662
            [3] => usr26976
            [4] => usr56486
        )

    [product_003] => Array
        (
            [0] => usr92895
            [1] => usr37642
            [2] => usr85241
        )

    [product_004] => Array
        (
            [0] => usr84327
            [1] => usr22985
        )

    [product_005] => Array
        (
            [0] => usr26819
        )

)
```

## Support

Having trouble? Create an issue!
