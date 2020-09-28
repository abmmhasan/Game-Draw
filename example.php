<?php

use AbmmHasan\LuckyDraw\LuckyDraw;

if(!function_exists('bcmul')){
    echo 'Requires php-bcmath Feature to be Enabled\n';
    echo 'For Ubuntu: apt install php7.1-bcmath\n';
    die;
}

$prizes=[
    [
        'item'=>'<span style="color:green">No Gift</span>',
        'chances'=>'100000',
        'amounts'=>[1]
    ],
    [
        'item'=>'<span style="color:green">Gift Box</span>',
        'chances'=>'1000',
        'amounts'=>[rand(1,100)]
    ],
    [
        'item'=>'<span style="color:#ff3bdb">Pen</span>',
        'chances'=>'500',
        'amounts'=>[
            1=>100,
            5=>50,
            10=>10
            ]
    ],
    [
        'item'=>'<span style="color:blue">Book</span>',
        'chances'=>'100',
        'amounts'=>[
            1=>100,
            5=>50,
            10=>10,
            20=>5
            ]
    ],
    [
        'item'=>'<span style="color:red">Bag</span>',
        'chances'=>'0.001',
        'amounts'=>[1]
    ],
];
//Drawing 100K lotteries
try{
    for($i=0;$i<100000;$i++){
        list($p,$c)=(new LuckyDraw($prizes))->draw();
        echo $p.'('.$c.'), ';
    }
}catch(\Exception $e){
    echo $e->getMessage();
}
