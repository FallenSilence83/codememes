<?php

$list = [
    "memes"=> []
];

$startId = 10000;
$count = 200;

for($i=0; $i<200; $i++){
    $list['memes'][] = [
        'memeId' => $startId+$i,
        'displayName'=> 'TodoName',
        'thumb' => 'todo',
        'url' => 'to',
        'youTubeKey' => null,
        'infoUrl' => null,
        'tags' => []
    ];
}


$file = fopen("generated.json","w");
echo fwrite($file, json_encode($list));
fclose($file);