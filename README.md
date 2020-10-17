# Game Draw

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)


The Lucky Draw class takes an example array (format is given in example file, will also explain below) and generates Item and Item count for winners.
The Mega Draw class takes 2 example array (format is given in example file, will also explain below) and generates winners for each prize according to given amount per prize.

> Please don't use this to generate things/prizes with People's hard earned money. It is intended to make things fun with bonus gifts only.


## Prerequisits

Language: PHP 7+

PHP Extension: BCMath (may need to install manually in Linux servers)

## Installation

```
composer require abmmhasan/game-draw
```

## Usage (Lucky Draw)

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

### Inventory Solutions

Available stock should be passed (after subtracting used amount from stock amount) in chances properly.

## Usage (Mega Draw)

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

To pass users where the Gifts are general:

```php
$users = 
['user01','user02','user03',..........,'userNNNNNNN']; // user identity
```

Or where the gifts are specifc per user group

```php
$users = 
[
    'user01'=>'product_002',        // user identity => Item Code/Identifier
    'user02'=>'product_003',
    'user03'=>'product_002',
    'user04'=>'product_001',
    .
    .
    'user NNNNNNN'=>'product_002'
];
```

### Output Data

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
            [10] => usr52595
            [11] => usr39674
            [12] => usr52520
            [13] => usr42316
            [14] => usr41327
            [15] => usr41461
            [16] => usr74861
            [17] => usr40589
            [18] => usr79599
            [19] => usr86757
            [20] => usr92409
            [21] => usr51569
            [22] => usr37905
            [23] => usr43123
            [24] => usr98934
            [25] => usr56999
            [26] => usr26529
            [27] => usr37097
            [28] => usr8417
            [29] => usr65328
            [30] => usr11656
            [31] => usr56668
            [32] => usr87999
            [33] => usr83457
            [34] => usr39765
            [35] => usr31917
            [36] => usr22395
            [37] => usr27971
            [38] => usr89124
            [39] => usr42330
            [40] => usr30652
            [41] => usr19458
            [42] => usr96018
            [43] => usr32073
            [44] => usr55307
            [45] => usr23103
            [46] => usr37772
            [47] => usr64712
            [48] => usr39795
            [49] => usr3161
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

To get Gift for General Case:

```php
print_r((new Megadraw())->get($prizes,$users));
```

To get Gift for Grouped Case:

```php
print_r((new Megadraw())->get($prizes,$users,true));
```

- We will pass the Formatted Input i.e. $prizes
- From the above example, (after execution) we will get Users won in each category.

## Support

Having trouble? Create an issue!
