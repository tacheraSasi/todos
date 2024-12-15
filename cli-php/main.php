<?php 

//a simple cli todo app built with php

$todos = array();

switch ($argv[1]) {
    case 'all':
        listAll($todos);
        break;
    
    default:
        _print("Invalid option choose a valid option please");
        break;
}

function _print($msg){
    print_r($msg."\n");
    
}

function listAll($todos){
    if (count($todos)==0){
        _print('No todo found');
        return;
    }
    foreach($todos as $todo){
        _print($todo."\n");
    }
}