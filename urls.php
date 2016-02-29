<?php

//Routes::$urls['welcome\/([0-9]+)\/(\w+)']=array('index', 'page');
use PhangoApp\PhaRouter\Routes;

Routes::$urls['shop\/viewproduct\/([0-9]+)\/(.*)']=array('viewproduct', 'index');

Routes::$urls['shop\/viewcategory\/([0-9]+)\/(.*)']=array('viewcategory', 'index');

Routes::$urls['shop\/viewcategory\/([0-9]+)']=['viewcategory', 'index'];

Routes::$urls['shop\/cart\/ajax\/cart_ajax_jscript']=['ajax/fjscript', 'index'];

Routes::$urls['shop\/cart\/ajax\/buy']=['ajax/buy', 'index'];

Routes::$urls['shop\/cart\/ajax\/checkoptionsproduct']=['ajax/checkoptionsproduct', 'index'];

Routes::$urls['shop\/cart\/ajax\/obtain_cart']=['ajax/obtaincart', 'index'];

Routes::$urls['shop\/cart\/update']=['cart', 'update'];

Routes::$urls['shop\/cart\/delete']=['cart', 'delete'];

Routes::$urls['shop\/cart\/get_address']=['cart', 'get_address'];

Routes::$urls['shop\/cart\/save_address']=['cart', 'save_address'];

Routes::$urls['shop\/cart\/get_user_save']=['cart', 'get_user_save'];

Routes::$urls['shop\/cart\/set_transport']=['cart', 'set_transport'];

Routes::$urls['shop\/cart\/save_transport_address']=['cart', 'save_transport_address'];

Routes::$urls['shop\/cart\/save_choose_address_transport']=['cart', 'save_choose_address_transport'];

Routes::$urls['shop\/cart\/set_method_transport']=['cart', 'set_method_transport'];

Routes::$urls['shop\/cart\/save_choose_transport']=['cart', 'save_choose_transport'];

Routes::$urls['shop\/cart\/checkout']=['cart', 'checkout'];

Routes::$urls['shop\/cart\/finish_checkout']=['cart', 'finish_checkout'];

Routes::$urls['shop\/cart\/finished']=['cart', 'finished'];

Routes::$urls['shop\/cart\/login']=['cart', 'login'];

Routes::$urls['shop\/cart\/logout']=['cart', 'logout'];

Routes::$urls['shop\/cart\/cancel']=['cart', 'cancel_order'];

Routes::$urls['shop\/cart\/recovery_password']=['cart', 'recovery_password'];

Routes::$urls['shop\/cart\/recovery_password_send']=['cart', 'recovery_password_send'];

Routes::$urls['shop\/cart']=['cart', 'index'];



?>