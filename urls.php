<?php

PhangoVar::$urls['shop']['viewproduct']=array('pattern' => '/^shop\/viewproduct\/([0-9]+)\/(\w+)$/', 'url' => '/shop/viewproduct', 'module' => 'shop', 'controller' => 'viewproduct', 'action' => 'index', 'parameters' => array('$1' => 'integer'));

?>