<?php

load_libraries(array('list_products_index'), $base_path.'/modules/shop/libraries/');

list_products_index('where cool=1 order by title ASC');

?>
