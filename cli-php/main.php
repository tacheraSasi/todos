<?php 

//a simple cli todo app built with php

$todos = array();

switch ($argv[1]) {
    case 'all':
        listAll($todos);
        break;
    
    default:
        print_r("Invalid option choose a valid option please");
        break;
}

function listAll($todos){
    if (count($todos)==0){
        print_r('No todo found');
        return;
    }
     foreach($todos as $todo){
        print_r($todo."\n");
     }
}