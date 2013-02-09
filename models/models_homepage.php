<?php

$model['homepage_shop']=new Webmodel('homepage_shop');


$model['homepage_shop']->components['position']=new IntegerField();
$model['homepage_shop']->components['idproduct']=new ForeignKeyField('product');

$model['homepage_shop']->components['idproduct']->fields_related_model=array('title');
$model['homepage_shop']->components['idproduct']->name_field_to_field='title';

?>
