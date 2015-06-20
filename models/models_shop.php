<?php

Utils::load_libraries(array('fields/i18nfield', 'fields/moneyfield', 'fields/passwordfield', 'models/userphangomodel'));

I18n::load_lang('shop');
I18n::load_lang('common');
I18n::load_lang('users');

//Countries. A Country is from a zone_shop

class country_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("country_shop");

	}	
	
	function insert($post)
	{
	
		$post=$this->components['name']->add_slugify_i18n_post('name', $post);
	
		return parent::insert($post);
	
	}
	
	function update($post, $conditions='')
	{
	
		$post=$this->components['name']->add_slugify_i18n_post('name', $post);
	
		return parent::update($post, $conditions);
	
	}
	
}

class zone_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("zone_shop");

	}

	function update($post, $conditions="")
	{

		settype($post['other_countries'], 'integer');
		settype($post['type'], 'integer');
		
		if($post['other_countries']==1 && isset($post['type']))
		{

			$num_count=parent::select_count('where other_countries=1 and type='.$post['type'], 'IdZone_shop');

			if($num_count>0)
			{

				$this->components['other_countries']->std_error=I18n::lang('shop', 'error_other_countries_is_selected', 'Error: se ha seleccionado que esta zona abarque el resto de países, pero no se ha especificado el tipo de zona');

				return 0;

			}

		}
		
		return parent::update($post, $conditions);

	}
	
}

Webmodel::$model['zone_shop']=new zone_shop();

Webmodel::$model['zone_shop']->register('name', 'I18nField', array(new CharField(255)), 1);

/*foreach($arr_i18n as PhangoVar::$lang_field)
{

	Webmodel::$model['zone_shop']->components['name_'.PhangoVar::$lang_field]=new CharField(255);
	Webmodel::$model['zone_shop']->components['name_'.PhangoVar::$lang_field]->required=1;

}*/

Webmodel::$model['zone_shop']->register('code', 'CharField', array(25), 1);

//Code 0 is for transport
//Code 1 is for taxes

Webmodel::$model['zone_shop']->register('type', 'IntegerField', array(11));
Webmodel::$model['zone_shop']->components['type']->form='HiddenForm';

Webmodel::$model['zone_shop']->register('other_countries', 'BooleanField', array());

Webmodel::$model['country_shop']=new country_shop();

/*foreach($arr_i18n as PhangoVar::$lang_field)
{

	Webmodel::$model['country_shop']->components['name_'.PhangoVar::$lang_field]=new CharField(255);
	Webmodel::$model['country_shop']->components['name_'.PhangoVar::$lang_field]->required=1;

}*/

Webmodel::$model['country_shop']->register('name', 'I18nField', array(new CharField(255)) , 1);

SlugifyField::add_slugify_i18n_fields('country_shop', 'name');

Webmodel::$model['country_shop']->register('code', 'CharField', array(25), 1);

//Webmodel::$model['country_shop']->components['idzone_taxes']=new ForeignKeyField('zone_shop');
Webmodel::$model['country_shop']->register('idzone_transport', 'ForeignKeyField', array(Webmodel::$model['zone_shop']));

//Users shop
//When delete, don't delete, only leave the data how user deleted.

/*class user_shop extends UserPhangoModel {

	public function update($post, $conditions="")
	{
	
		if(isset($post['password']))
		{
		
			if($this->components['password']->check($post['password'])=='')
			{
			
				$this->components['password']->required=0;
				unset($post['password']);
			
			}
		
		}
	
		return parent::update($post, $conditions);
	
	}

}*/

Webmodel::$model['user_shop']=new UserPhangoModel('user_shop');

//Webmodel::$model['user_shop']=new user_shop('user_shop');

Webmodel::$model['user_shop']->username='email';

Webmodel::$model['user_shop']->register('email', 'CharField', array(255), 1);

Webmodel::$model['user_shop']->register('password', 'PasswordField', array(255), 1);

Webmodel::$model['user_shop']->register('token_client', 'CharField', array(255), 1);

Webmodel::$model['user_shop']->register('token_recovery', 'CharField', array(255), 1);

Webmodel::$model['user_shop']->register('name', 'CharField', array(255), 1);

Webmodel::$model['user_shop']->register('last_name', 'CharField', array(255), 1);
Webmodel::$model['user_shop']->register('address', 'CharField', array(255), 1);
Webmodel::$model['user_shop']->register('zip_code', 'CharField', array(255), 1);
Webmodel::$model['user_shop']->register('region', 'CharField', array(255), 1);
Webmodel::$model['user_shop']->register('city', 'CharField', array(255), 1);
Webmodel::$model['user_shop']->register('country', 'ForeignKeyField', array(Webmodel::$model['country_shop']), 1);
Webmodel::$model['user_shop']->register('phone', 'CharField', array(255), 1);//Only for special effects...
Webmodel::$model['user_shop']->register('fax', 'CharField', array(255));//Only for special effects...
Webmodel::$model['user_shop']->register('nif', 'CharField', array(255), 1);//Only for special effects...
Webmodel::$model['user_shop']->register('enterprise_name', 'CharField', array(255));//Only for special effects...
Webmodel::$model['user_shop']->register('last_connection', 'IntegerField', array(11));
Webmodel::$model['user_shop']->register('format_date', 'ChoiceField', array(10, 'string', array('d-m-Y', 'Y-m-d')));
Webmodel::$model['user_shop']->register('format_time', 'IntegerField', array(11));
Webmodel::$model['user_shop']->register('timezone', 'ChoiceField', array(35, 'string', array(), MY_TIMEZONE));
Webmodel::$model['user_shop']->register('ampm', 'ChoiceField', array(10, 'string', array('H:i:s', 'h:i:s A'), MY_TIMEZONE));

Webmodel::$model['user_shop']->register('disabled', 'BooleanField', array());

Webmodel::$model['country_user_shop']=new Webmodel('country_user_shop');

Webmodel::$model['country_user_shop']->components['iduser']=new IntegerField(11);
Webmodel::$model['country_user_shop']->components['idcountry']=new IntegerField(11);

Webmodel::$model['currency']=new currency('currency');

Webmodel::$model['currency']->register('name', 'I18nField', array(new TextField()) , 1);

Webmodel::$model['currency']->register('symbol', 'CharField', array(25), 1);

class currency_change extends Webmodel {

	function __construct()
	{

		parent::__construct("currency_change");

	}

	function insert($post)
	{

		settype($post['idcurrency'], 'integer');
		settype($post['idcurrency_related'], 'integer');

		$num_change=$this->select_count('where idcurrency_related='.$post['idcurrency_related'].' and idcurrency='.$post['idcurrency'], 'IdCurrency_change');

		if($num_change==0)
		{

			return parent::insert($post);

		}
		else
		{

			$this->std_error=I18n::lang('shop', 'this_currency_have_equivalence', 'Ya ha aplicado un valor a esta moneda. Edite la relación correspondiente creada anteriormente, o cambie la moneda a la que quiere aplicar la equivalencia');
			return 0;

		}

	}

	function update($post, $conditions='')
	{

		//We need see if exists the row with this data

		settype($post['idcurrency_related'], 'integer');

		$query=$this->select('where idcurrency_related='.$post['idcurrency_related'].' and idcurrency='.$post['idcurrency'], array('IdCurrency_change'));

		list($idcurrency_change)=$this->fetch_row($query);

		settype($idcurrency_change, 'integer');

		//Well, use $idcurrency_change how conditions...

		$conditions='where IdCurrency_change='.$idcurrency_change;

		return parent::update($post, $conditions);

	}
	
}

Webmodel::$model['currency_change']=new currency_change('currency_change');

Webmodel::$model['currency_change']->register('idcurrency', 'ParentField', array(Webmodel::$model['currency'], 11), 1);

Webmodel::$model['currency_change']->register('idcurrency_related', 'ForeignKeyField', array(Webmodel::$model['currency'], 11), 1);
Webmodel::$model['currency_change']->components['idcurrency_related']->name_field_to_field='name';
//Webmodel::$model['currency_change']->components['idcurrency_related']->fields_related_model=array('name');

Webmodel::$model['currency_change']->register('change_value', 'MoneyField', array(), 1);

//Product class

class product extends Webmodel {

	public function __construct()
	{

		parent::__construct("product");

	}
	
	public function insert($post)
	{
	
		$post=$this->components['title']->add_slugify_i18n_post('title', $post);
	
		if(parent::insert($post))
		{
		
			settype($_GET['idcat'], 'integer');
		
			$idproduct=parent::insert_id();
		
			if( Webmodel::$model['product_relationship']->insert(array('idproduct' => $idproduct, 'idcat_product' => $_GET['idcat'])) )
			{
				$this->std_error='Cannot insert a new relationship';
			}
			
			return true;
		
		}
		else
		{
		
			return false;
		
		}
	
	}
	
	public function update($post, $conditions='')
	{
	
		$post=$this->components['title']->add_slugify_i18n_post('title', $post);
	
		return parent::update($post, $conditions);
	
	}
	
	public function delete($conditions="")
	{
	
		//Obtain ids for this product for delete images of product.
		
		$query=$this->select($conditions, array('IdProduct'));
		
		$arr_id_prod=array(0);
		
		while(list($idproduct)=$this->fetch_row($query))
		{
		
			$arr_id_prod[]=$idproduct;
		
		}
		
		Webmodel::$model['image_product']->delete('where image_product.idproduct IN ('.implode(', ', $arr_id_prod).')');
		
		Webmodel::$model['product_relationship']->delete('where product_relationship.idproduct IN ('.implode(', ', $arr_id_prod).')');
		
		if(!parent::delete($conditions))
		{
		
			echo '<p>Este producto está en facturación, por lo tanto se desactivará pero no se borrará</p>';
			
			return false;
		
		}
		else
		{
		
			return true;
		
		}
	
	}
	
}

Webmodel::$model['product']=new product();

Webmodel::$model['product']->register('referer', 'CharField', array(255), 1);

Webmodel::$model['product']->register('title', 'I18nField', array(new CharField(255)), 1);

SlugifyField::add_slugify_i18n_fields('product', 'title');

Webmodel::$model['product']->register('description', 'I18nField', array(new TextHTMLField()), 1);

Webmodel::$model['product']->register('description_short', 'I18nField', array(new CharField(1000)));

//Webmodel::$model['product']->components['idcat']=new ForeignKeyField('cat_product', 11);

//Webmodel::$model['product']->components['idcat']->required=1;

Webmodel::$model['product']->register('price', 'MoneyField', array());

Webmodel::$model['product']->register('special_offer', 'DoubleField', array());

Webmodel::$model['product']->register('stock', 'BooleanField', array());

Webmodel::$model['product']->register('date', 'DateField', array());

Webmodel::$model['product']->register('about_order', 'BooleanField', array());

//Webmodel::$model['product']->components['extra_options']=new ChoiceField(255, 'string');

Webmodel::$model['product']->register('weight', 'DoubleField', array());

Webmodel::$model['product']->register('num_sold', 'IntegerField', array());

Webmodel::$model['product']->register('cool', 'BooleanField', array());

class image_product extends Webmodel {

	function __construct()
	{

		parent::__construct("image_product");

	}

	function insert($post)
	{
		
		settype($post['principal'], 'integer');
		settype($post['idproduct'], 'integer');

		//If is not defined idproduct cannot change principal image.

		if($post['idproduct']>0)
		{

			$num_principal_photo=$this->select_count('where principal=1 and idproduct='.$post['idproduct'], 'IdImage_product');

			if($num_principal_photo==0)
			{

				$post['principal']=1;

			}

			if($post['principal']==1)
			{

				$query=MySQLClass::webtsys_query('update image_product set principal=0 where idproduct='.$post['idproduct']);

			}

		}
		else
		{

			//Unset principal

			unset($post['principal']);

		}

		return Webmodel::insert($post);

	}
	
	function update($post, $conditions="")
	{
		
		settype($post['principal'], 'integer');
		settype($post['idproduct'], 'integer');

		//If is not defined idproduct cannot change principal image.
		
		//$query=$this->select('where idproduct='.);
		
		//Array ( [photo] => arcoiris_jaen.jpg [idproduct] => 10 [principal] => 1 ) 
		
			//if exists a new photo...
		
		if($post['idproduct']>0)
		{

			$num_principal_photo=$this->select_count('where principal=1 and idproduct='.$post['idproduct'], 'IdImage_product');

			if($num_principal_photo==0)
			{

				$post['principal']=1;

			}

			if($post['principal']==1)
			{

				$query=MySQLClass::webtsys_query('update image_product set principal=0 where idproduct='.$post['idproduct']);

			}

		}
		else
		{

			//Unset principal

			unset($post['principal']);

		}
		
		$return_file=Webmodel::update($post, $conditions);
		
		/*if($return_file==1)
		{
		
			if($post['photo']!=$_FILES['photo']['name'])
			{
			
				//Delete old photo...
				
				foreach($this->components['photo']->img_width as $name_width => $width)
				{
				

					if(!unlink($this->components['photo']->path.'/'.$name_width.'_'.$post['photo']))
					{
					
						//die;
					
					}

				}
			
			}
			
		}*/


		return $return_file;

	}

	function delete($conditions="")
	{

		//Delete images from field...
		
		$query=$this->select($conditions, array('IdImage_product', 'principal', 'photo', 'idproduct'));

		while(list($idimage, $principal, $photo, $idproduct)=$this->fetch_row($query))
		{
			
			if($photo!='')
			{
				//echo $this->components['photo']->path.'/'.$photo;

				if(unlink($this->components['photo']->path.'/'.$photo))
				{

					foreach($this->components['photo']->img_width as $name_width => $width)
					{
					

						if(!unlink($this->components['photo']->path.'/'.$name_width.'_'.$photo))
						{
						
							//die;
						
						}

					}
					
				}
				else
				{
				
					//die;
				
				}

			}

			if($principal==1)
			{
				
				$query2=MySQLClass::webtsys_query('update image_product set principal=1 where idproduct='.$idproduct.' and IdImage_product!='.$idimage.' limit 1');

			}

		}

 		return MySQLClass::webtsys_query('delete from '.$this->name.' '.$conditions);
		
	}

}

Webmodel::$model['image_product']=new image_product();

Webmodel::$model['image_product']->register('principal', 'BooleanField', array());
Webmodel::$model['image_product']->register('photo', 'ImageField', array('photo', PhangoVar::$base_path.'/shop/products/images/', Routes::$root_url.'/shop/products/images', 'image', 1, array('small' => 45, 'mini' => 150, 'medium' => 300, 'preview' => 600)), 1);
Webmodel::$model['image_product']->register('idproduct', 'ForeignKeyField', array(Webmodel::$model['product'], 11), 1);


class cat_product extends Webmodel {

	function __construct()
	{

		parent::__construct("cat_product");

	}	
	
	function delete($conditions="")
	{
		
		$arr_id_cat_product=array(0);
		
		$query=$this->select($conditions, array('IdCat_product'));
		
		while(list($idcat_product)=$this->fetch_row($query))
		{
		
			//$query=Webmodel::$model['product']->delete('where idcat='.$idcat_product);
			$arr_idcat_product[]=$idcat_product;
		
		}
		
		//Delete relationships...
		
		Webmodel::$model['product_relationship']->delete('where idcat_product IN ('.implode(',', $arr_idcat_product).')');
	
		return parent::delete($conditions);
	
	}

}

Webmodel::$model['cat_product']=new cat_product();

$field_title_cat=new TextHTMLField();

Webmodel::$model['cat_product']->register('title', 'I18nField', array($field_title_cat), 1);

Webmodel::$model['cat_product']->register('subcat', 'ParentField', array(Webmodel::$model['cat_product'], 255));

Webmodel::$model['cat_product']->register('description', 'I18nField', array(new TextHTMLField()) , 1);

Webmodel::$model['cat_product']->register('view_only_mode', 'BooleanField', array());

Webmodel::$model['cat_product']->register('position', 'IntegerField', array());

Webmodel::$model['cat_product']->register('image_cat', 'ImageField', array('image_cat', PhangoVar::$base_path.'/shop/categories/images/', PhangoVar::$base_url.'/shop/categories/images/', 'image', 0) );

class product_relationship extends Webmodel {

	function insert($post)
	{
		
		if( !$this->select_count('where idproduct='.$post['idproduct'].' and idcat_product='.$post['idcat_product'], 'IdProduct_relationship') )
		{
		
			return parent::insert($post);
		
		}
		else
		{
			$this->std_error=I18n::lang('shop', 'product_is_already_on_category', 'Este producto está realmente en la categoría');
		
			return false;
		
		}
	
	}

	function update($post, $conditions="")
	{
		
		if( !$this->select_count('where idproduct='.$post['idproduct'].' and idcat_product='.$post['idcat_product'], 'IdProduct_relationship') )
		{
		
			return parent::update($post, $conditions);
		
		}
		else
		{
			$this->std_error=I18n::lang('shop', 'product_is_already_on_category', 'Este producto está realmente en la categoría');
		
			return false;
		
		}
	
	}
	
}

Webmodel::$model['product_relationship']=new product_relationship('product_relationship');

Webmodel::$model['product_relationship']->register('idproduct', 'ForeignKeyField', array(Webmodel::$model['product'], 11), 1);

Webmodel::$model['product_relationship']->register('idcat_product', 'ForeignKeyField', array(Webmodel::$model['cat_product'], 11), 1);
Webmodel::$model['product_relationship']->components['idcat_product']->name_field_to_field='title';


/*class taxes extends Webmodel {

	function __construct()
	{

		parent::__construct("taxes");

	}	
	
}

Webmodel::$model['taxes']=new taxes();

Webmodel::$model['taxes']->components['name']=new CharField(255);
Webmodel::$model['taxes']->components['name']->required=1;

Webmodel::$model['taxes']->components['percent']=new DoubleField(255);

Webmodel::$model['taxes']->components['country']=new ForeignKeyField('zone_shop');
Webmodel::$model['taxes']->components['country']->required=1;*/

class transport extends Webmodel {

	function __construct()
	{

		parent::__construct("transport");

	}	
	
}

Webmodel::$model['transport']=new transport();

Webmodel::$model['transport']->register('name', 'CharField', array(255), 1);

Webmodel::$model['transport']->register('country', 'ForeignKeyField', array(Webmodel::$model['zone_shop']), 1);

Webmodel::$model['transport']->register('type', 'ChoiceField', array($size=11, $type='integer', $arr_values=array(0, 1), $default_value=0));

class price_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport");

	}	
	
}

Webmodel::$model['price_transport']=new price_transport();

Webmodel::$model['price_transport']->register('price', 'MoneyField', array());
Webmodel::$model['price_transport']->components['price']->required=1;

Webmodel::$model['price_transport']->register('weight', 'DoubleField', array());
Webmodel::$model['price_transport']->components['weight']->required=0;

Webmodel::$model['price_transport']->register('idtransport', 'ForeignKeyField', array(Webmodel::$model['transport']));
Webmodel::$model['price_transport']->components['idtransport']->form='HiddenForm';
Webmodel::$model['price_transport']->components['idtransport']->required=1;

class price_transport_price extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport_price");

	}	
	
}

Webmodel::$model['price_transport_price']=new price_transport_price();

Webmodel::$model['price_transport_price']->components['price']=new MoneyField();
Webmodel::$model['price_transport_price']->components['price']->required=0;

Webmodel::$model['price_transport_price']->components['min_price']=new MoneyField();
Webmodel::$model['price_transport_price']->components['min_price']->required=0;

Webmodel::$model['price_transport_price']->components['idtransport']=new ForeignKeyField(Webmodel::$model['transport']);
Webmodel::$model['price_transport_price']->components['idtransport']->form='HiddenForm';
Webmodel::$model['price_transport_price']->components['idtransport']->required=1;


class config_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("config_shop");

	}

	function update($post, $conditions='')
	{

		settype($_POST['num_begin_bill'], 'integer');

		if($_POST['num_begin_bill']<0)
		{

			$_POST['num_begin_bill']=1;

		}

		$query=MySQLClass::webtsys_query('ALTER TABLE order_shop AUTO_INCREMENT = '.$_POST['num_begin_bill']);
		

		return Webmodel::update($post, $conditions);

	}
	
}

Webmodel::$model['config_shop']=new config_shop();

Webmodel::$model['config_shop']->register('num_news', 'IntegerField', array(11));

/*Webmodel::$model['config_shop']->components['num_news']=new IntegerField(11);
Webmodel::$model['config_shop']->components['num_news']->required=1;*/
/*Webmodel::$model['config_shop']->components['yes_taxes']=new BooleanField();*/
$field_conditions=new TextHTMLField();
Webmodel::$model['config_shop']->components['conditions']=new I18nField($field_conditions);
//create_field_multilang('config_shop', 'conditions', $field_conditions, 0);
Webmodel::$model['config_shop']->components['no_transport']=new BooleanField();
//Webmodel::$model['config_shop']->components['type_index']=new CharField(25);
//Webmodel::$model['config_shop']->components['ssl_url']=new BooleanField();

$field_title_shop=new TextHTMLField();
Webmodel::$model['config_shop']->components['title_shop']=new I18nField($field_title_shop);
//create_field_multilang('config_shop', 'title_shop', $field_title_shop, 0);*/
//Webmodel::$model['config_shop']->components['description_shop']->multilang=1;
$field_description_shop=new TextHTMLField();
Webmodel::$model['config_shop']->components['description_shop']=new I18nField($field_description_shop);
//create_field_multilang('config_shop', 'description_shop', $field_description_shop, 0);
//Webmodel::$model['config_shop']->components['cart_style']=new IntegerField(11);
//Webmodel::$model['config_shop']->components['idtax']=new ForeignKeyField('taxes', 11);
Webmodel::$model['config_shop']->components['head_bill']=new CharField(255);
Webmodel::$model['config_shop']->components['num_begin_bill']=new IntegerField(11);
Webmodel::$model['config_shop']->components['elements_num_bill']=new IntegerField(11);
Webmodel::$model['config_shop']->components['image_bill']=new ImageField('image_bill', PhangoVar::$base_path.'/shop/bill/images/', Routes::$root_url.'/shop/bill/images', 'image', 0);

Webmodel::$model['config_shop']->components['bill_data_shop']=new TextField();
Webmodel::$model['config_shop']->components['bill_data_shop']->form='TextAreaForm';
Webmodel::$model['config_shop']->components['bill_data_shop']->br=0;
Webmodel::$model['config_shop']->components['footer_bill']=new TextField();
Webmodel::$model['config_shop']->components['footer_bill']->form='TextAreaForm';
Webmodel::$model['config_shop']->components['footer_bill']->br=0;

/*Webmodel::$model['config_shop']->components['explain_discounts_page']=new ForeignKeyField('page', 11);
Webmodel::$model['config_shop']->components['explain_discounts_page']->container_model='pages';*/

Webmodel::$model['config_shop']->components['idcurrency']=new ForeignKeyField(Webmodel::$model['currency'], 11);
Webmodel::$model['config_shop']->components['idcurrency']->required=1;

Webmodel::$model['config_shop']->components['view_only_mode']=new BooleanField();

class address_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("address_transport");

	}	
	
}

Webmodel::$model['address_transport']=new address_transport();

Webmodel::$model['address_transport']->register('iduser', 'ForeignKeyField', array(Webmodel::$model['user_shop']), 1);
Webmodel::$model['address_transport']->register('name_transport', 'CharField', array(255), 1);
Webmodel::$model['address_transport']->register('last_name_transport', 'CharField', array(255), 1);
Webmodel::$model['address_transport']->register('enterprise_name_transport', 'CharField', array(255));
Webmodel::$model['address_transport']->register('address_transport', 'CharField', array(255), 1);
Webmodel::$model['address_transport']->register('zip_code_transport', 'CharField', array(255), 1);
Webmodel::$model['address_transport']->register('phone_transport', 'CharField', array(255), 1);
Webmodel::$model['address_transport']->register('city_transport', 'CharField', array(255), 1);
Webmodel::$model['address_transport']->register('region_transport', 'CharField', array(255), 1);
Webmodel::$model['address_transport']->register('country_transport', 'ForeignKeyField', array(Webmodel::$model['country_shop'], 11), 1);
//Webmodel::$model['address_transport']->register('zone_transport', 'ForeignKeyField', array('zone_shop', 11));

class payment_form extends Webmodel {

	function __construct()
	{

		parent::__construct("payment_form");

	}	
	
}

Webmodel::$model['payment_form']=new payment_form();
Webmodel::$model['payment_form']->register('name', 'I18nField', array(new TextField()) , 1);
Webmodel::$model['payment_form']->register('code', 'ChoiceField', array(255, 'string'));
Webmodel::$model['payment_form']->register('price_payment', 'MoneyField', array() );

class cart_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("cart_shop");

	}	
	
}

Webmodel::$model['cart_shop']=new cart_shop();
Webmodel::$model['cart_shop']->register('token', 'CharField', array(255));
Webmodel::$model['cart_shop']->register('idproduct', 'ForeignKeyField', array(Webmodel::$model['product'], 11));
Webmodel::$model['cart_shop']->components['idproduct']->fields_related_model=array('referer', 'title');
Webmodel::$model['cart_shop']->register('price_product', 'MoneyField', array());
/*Webmodel::$model['cart_shop']->components['name_taxes_product']=new DoubleField();
Webmodel::$model['cart_shop']->components['taxes_product']=new DoubleField();*/
Webmodel::$model['cart_shop']->register('units', 'IntegerField', array());
Webmodel::$model['cart_shop']->register('details', 'ArrayField', array(new CharField(255)));
Webmodel::$model['cart_shop']->register('alter_price_elements', 'ArrayField', array(new MoneyField()));
Webmodel::$model['cart_shop']->register('time', 'IntegerField', array());
Webmodel::$model['cart_shop']->register('weight', 'DoubleField', array());

class order_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("order_shop");

	}	
	
	static public function calculate_num_bill($idorder_shop)
	{

		settype($idorder_shop, 'string');

		$num_elements_num_bill=strlen($idorder_shop);
		$num_bill_tmp='';

		if($num_elements_num_bill<ConfigShop::$config_shop['elements_num_bill'])
		{

			$count_elements_num_bill=ConfigShop::$config_shop['elements_num_bill']-$num_elements_num_bill;

			for($x=0;$x<$count_elements_num_bill;$x++)
			{

				$num_bill_tmp.='0';

			}

		}

		$num_bill_tmp.=$idorder_shop;

		$num_bill=ConfigShop::$config_shop['head_bill'].$num_bill_tmp;

		return $num_bill;

	}
	
	/*
	function update($post, $conditions='')
	{
	
		global PhangoVar::$model;
		
		if(isset($post['make_payment']))
		{
			
			if($post['make_payment']==1)
			{
				
				//Insert num order if not exists.
				
				$query=$this->select($conditions, array('invoice_num', 'token'), 1);
				
				list($invoice_num, $token)=webtsys_fetch_row($query);
						
				if($invoice_num=='')
				{
					
					//Insert on invoice_num
					
					Webmodel::$model['invoice_num']->insert(array('token_shop' => $token));
					
					$num_order=webtsys_insert_id();

					$this->reset_require();
					
				}
				else
				{
				
					$num_order=$invoice_num;
				
				}
					
				//$this->update(array('make_payment' => 1, 'invoice_num' => $num_order), 'where token="'.$token.'"');
				//Raw update
				
				//$query=webtsys_query('update order_shop set make_payment=1, invoice_num="'.$num_order.'" where token="'.$token.'"');
				$post['invoice_num']=$num_order;
			
			}
		
		}
	
		return parent::update($post, $conditions);
	
	}*/
}

Webmodel::$model['order_shop']=new order_shop();

Webmodel::$model['order_shop']->components['token']=new CharField(255);
Webmodel::$model['order_shop']->components['referer']=new CharField(255);
Webmodel::$model['order_shop']->components['name']=new CharField(255);
Webmodel::$model['order_shop']->components['last_name']=new CharField(255);
Webmodel::$model['order_shop']->components['enterprise_name']=new CharField(255);
Webmodel::$model['order_shop']->components['email']=new CharField(255);
Webmodel::$model['order_shop']->components['nif']=new CharField(255);
Webmodel::$model['order_shop']->components['address']=new CharField(255);
Webmodel::$model['order_shop']->components['zip_code']=new CharField(255);
Webmodel::$model['order_shop']->components['city']=new CharField(255);
Webmodel::$model['order_shop']->components['region']=new CharField(255);
Webmodel::$model['order_shop']->components['country']=new I18nField(new TextField());
Webmodel::$model['order_shop']->components['phone']=new CharField(255);
Webmodel::$model['order_shop']->components['fax']=new CharField(255);

Webmodel::$model['order_shop']->components['name_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['last_name_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['enterprise_name_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['address_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['zip_code_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['city_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['region_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['country_transport']=new I18nField(new TextField());
Webmodel::$model['order_shop']->components['phone_transport']=new CharField(255);
Webmodel::$model['order_shop']->components['address_transport_id']=new IntegerField(11);

Webmodel::$model['order_shop']->components['transport']=new CharField(255);
Webmodel::$model['order_shop']->components['price_transport']=new MoneyField();

Webmodel::$model['order_shop']->components['name_payment']=new CharField(255);
Webmodel::$model['order_shop']->components['price_payment']=new MoneyField();

Webmodel::$model['order_shop']->components['finished']=new BooleanField();

Webmodel::$model['order_shop']->components['payment_done']=new BooleanField();

Webmodel::$model['order_shop']->components['observations']=new TextHTMLField();

Webmodel::$model['order_shop']->components['date_order']=new DateField();

Webmodel::$model['order_shop']->register('iduser', 'ForeignKeyField', array(Webmodel::$model['user_shop']), 1);

//Webmodel::$model['order_shop']->components['iduser']=new ForeignKeyField('user_shop');

//Webmodel::$model['order_shop']->components['payment_discount_percent']=new PercentField();

Webmodel::$model['order_shop']->components['total_price']=new MoneyField();

/*Webmodel::$model['order_shop']->components['invoice_num']=new ForeignKeyField('invoice_num');
Webmodel::$model['order_shop']->components['invoice_num']->name_field_to_field='invoice_num';
*/

Webmodel::$model['order_shop']->components['name']->required=1;	
Webmodel::$model['order_shop']->components['last_name']->required=1;
Webmodel::$model['order_shop']->components['email']->required=1;
Webmodel::$model['order_shop']->components['address']->required=1;
Webmodel::$model['order_shop']->components['zip_code']->required=1;
Webmodel::$model['order_shop']->components['city']->required=1;
Webmodel::$model['order_shop']->components['region']->required=1;
Webmodel::$model['order_shop']->components['country']->required=1;
Webmodel::$model['order_shop']->components['phone']->required=1;

Webmodel::$model['order_shop']->components['name_transport']->required=1;	
Webmodel::$model['order_shop']->components['last_name_transport']->required=1;
Webmodel::$model['order_shop']->components['address_transport']->required=1;
Webmodel::$model['order_shop']->components['zip_code_transport']->required=1;
Webmodel::$model['order_shop']->components['city_transport']->required=1;
Webmodel::$model['order_shop']->components['region_transport']->required=1;
Webmodel::$model['order_shop']->components['country_transport']->required=1;
//Webmodel::$model['order_shop']->components['zone_transport']->required=1;
Webmodel::$model['order_shop']->components['phone_transport']->required=1;

Webmodel::$model['order_shop']->components['token']->required=1;
Webmodel::$model['order_shop']->components['transport']->required=1;
//Webmodel::$model['order_shop']->components['name_payment']->required=1;
Webmodel::$model['order_shop']->components['price_payment']->required=0;

Webmodel::$model['order_shop']->create_form();

Webmodel::$model['order_shop']->forms['referer']->label=I18n::lang('shop', 'referer', 'Referencia');
Webmodel::$model['order_shop']->forms['name']->label=I18n::lang('users', 'name', 'Name');
Webmodel::$model['order_shop']->forms['last_name']->label=I18n::lang('users', 'last_name', 'Lastname');
Webmodel::$model['order_shop']->forms['enterprise_name']->label=I18n::lang('users', 'enterprise_name', 'Enterprise name');
Webmodel::$model['order_shop']->forms['email']->label=I18n::lang('users', 'email', 'Email');
Webmodel::$model['order_shop']->forms['nif']->label=I18n::lang('users', 'nif', 'Nif');
Webmodel::$model['order_shop']->forms['address']->label=I18n::lang('common', 'address', 'Address');
Webmodel::$model['order_shop']->forms['zip_code']->label=I18n::lang('users', 'zip_code', 'Zip code');
Webmodel::$model['order_shop']->forms['city']->label=I18n::lang('users', 'city', 'City');
Webmodel::$model['order_shop']->forms['region']->label=I18n::lang('common', 'region', 'Region');
Webmodel::$model['order_shop']->forms['country']->label=I18n::lang('common', 'country', 'Country');
Webmodel::$model['order_shop']->forms['phone']->label=I18n::lang('common', 'phone', 'Phone');
Webmodel::$model['order_shop']->forms['fax']->label=I18n::lang('common', 'fax', 'Fax');
Webmodel::$model['order_shop']->forms['name_transport']->label=I18n::lang('users', 'name', 'Name');
Webmodel::$model['order_shop']->forms['last_name_transport']->label=I18n::lang('users', 'last_name', 'Lastname');
Webmodel::$model['order_shop']->forms['enterprise_name_transport']->label=I18n::lang('users', 'enterprise_name', 'Enterprise name');
Webmodel::$model['order_shop']->forms['address_transport']->label=I18n::lang('common', 'address', 'Address');
Webmodel::$model['order_shop']->forms['zip_code_transport']->label=I18n::lang('common', 'zip_code', 'Zip code');
Webmodel::$model['order_shop']->forms['city_transport']->label=I18n::lang('common', 'city', 'City');
Webmodel::$model['order_shop']->forms['region_transport']->label=I18n::lang('common', 'region', 'Region');
Webmodel::$model['order_shop']->forms['country_transport']->label=I18n::lang('common', 'country', 'Country');
Webmodel::$model['order_shop']->forms['phone_transport']->label=I18n::lang('common', 'phone', 'Phone');
//Webmodel::$model['order_shop']->forms['zone_transport']->label=I18n::lang('shop', 'zone', 'Zona');
Webmodel::$model['order_shop']->forms['transport']->label=I18n::lang('shop', 'transport', 'Transporte');
Webmodel::$model['order_shop']->forms['payment_done']->label=I18n::lang('shop', 'make_payment', '¿Pagado?');
Webmodel::$model['order_shop']->forms['observations']->label=I18n::lang('shop', 'observations', 'Observaciones');
Webmodel::$model['order_shop']->forms['date_order']->label=I18n::lang('common', 'date', 'date');
//Webmodel::$model['order_shop']->forms['invoice_num']->label=I18n::lang('shop', 'invoice_num', 'Número de factura');

Webmodel::$model['order_shop_plugins']=new Webmodel('order_shop_plugins');

Webmodel::$model['order_shop_plugins']->register('idorder_shop', 'ForeignKeyField', array(Webmodel::$model['order_shop']));
Webmodel::$model['order_shop_plugins']->register('name', 'I18nField', array('order_shop'));
Webmodel::$model['order_shop_plugins']->register('add_price', 'MoneyField', array());
Webmodel::$model['order_shop_plugins']->register('idcart_shop', 'ForeignKeyField', array(Webmodel::$model['cart_shop']));

Webmodel::$model['invoice_num']=new Webmodel('invoice_num');

Webmodel::$model['invoice_num']->change_id_default('invoice_num');

Webmodel::$model['invoice_num']->components['token_shop']=new CharField(255);

class type_product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("type_product_option");

	}	
	
}

Webmodel::$model['type_product_option']=new type_product_option();

Webmodel::$model['type_product_option']->components['title']=new I18nField(new TextField());
Webmodel::$model['type_product_option']->components['title']->required=1;

Webmodel::$model['type_product_option']->components['description']=new I18nField(new TextField());
Webmodel::$model['type_product_option']->components['description']->required=1;

Webmodel::$model['type_product_option']->components['question']=new I18nField(new TextField());
Webmodel::$model['type_product_option']->components['question']->required=1;

Webmodel::$model['type_product_option']->components['options']=new I18nField(new TextField());
Webmodel::$model['type_product_option']->components['options']->required=0;

Webmodel::$model['type_product_option']->components['price']=new MoneyField();
Webmodel::$model['type_product_option']->components['price']->required=0;


class product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("product_option");

	}	
	
}

Webmodel::$model['product_option']=new product_option();

Webmodel::$model['product_option']->components['idtype']=new ForeignKeyField(Webmodel::$model['type_product_option'], 11);

Webmodel::$model['product_option']->components['idtype']->required=1;

Webmodel::$model['product_option']->components['idtype']->fields_related_model=array('title');
Webmodel::$model['product_option']->components['idtype']->name_field_to_field='title';

Webmodel::$model['product_option']->components['idproduct']=new ForeignKeyField(Webmodel::$model['product'], 11);

Webmodel::$model['product_option']->components['idproduct']->form='HiddenForm';

Webmodel::$model['product_option']->components['idproduct']->required=1;

Webmodel::$model['product_option']->components['field_required']=new BooleanField();


class group_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop");

	}	
	
}

Webmodel::$model['group_shop']=new group_shop();

Webmodel::$model['group_shop']->components['name']=new I18nField(new CharField(255));
Webmodel::$model['group_shop']->components['name']->required=1;
Webmodel::$model['group_shop']->components['discount']=new PercentField(11);
//Webmodel::$model['group_shop']->components['taxes_for_group']=new PercentField(11);
Webmodel::$model['group_shop']->components['transport_for_group']=new PercentField(11);
Webmodel::$model['group_shop']->components['shipping_costs_for_group']=new PercentField(11);

class group_shop_users extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop_users");

	}	
	
}

Webmodel::$model['group_shop_users']=new group_shop_users();

Webmodel::$model['group_shop_users']->components['iduser']=new ForeignKeyField(Webmodel::$model['user_shop'], 11);
Webmodel::$model['group_shop_users']->components['iduser']->required=1;
Webmodel::$model['group_shop_users']->components['iduser']->fields_related_model=array('private_nick');
Webmodel::$model['group_shop_users']->components['iduser']->name_field_to_field='private_nick';
Webmodel::$model['group_shop_users']->components['group_shop']=new ForeignKeyField(Webmodel::$model['group_shop'], 11);
Webmodel::$model['group_shop_users']->components['group_shop']->form='HiddenForm';
Webmodel::$model['group_shop_users']->components['group_shop']->required=1;
Webmodel::$model['group_shop_users']->components['group_shop']->container_model='shop';

//Currency
class currency extends Webmodel {

	function __construct()
	{

		parent::__construct("currency");

	}

	function delete($conditions="")
	{

		//Cannot delete all and cannot delete currency selected...

		$arr_id=array(0);

		$query=$this->select($conditions, array('IdCurrency'));

		while(list($idcurrency)=$this->fetch_row($query))
		{

			$arr_id[]=$idcurrency;

			if(ConfigShop::$config_shop['idcurrency']==$idcurrency)
			{

				return 0;

			}

		}

		$query=Webmodel::$model['currency_change']->delete('where idcurrency IN ('.implode(', ', $arr_id).') or idcurrency_change IN ('.implode(', ', $arr_id).')');

		return parent::delete($conditions);

	}

}

//Class plugin_shop

Webmodel::$model['plugin_shop']=new Webmodel('plugin_shop');

Webmodel::$model['plugin_shop']->register('name', 'CharField', array(255), 1);

Webmodel::$model['plugin_shop']->register('element', 'ChoiceField', array($size=255, $type='string', $arr_values=array('product', 'cart', 'discounts'), $default_value=''), 1);

Webmodel::$model['plugin_shop']->register('plugin', 'ChoiceField', array($size=255, $type='string', $arr_values=array(''), $default_value=''), 1);

Webmodel::$model['plugin_shop']->register('position', 'IntegerField', array() );

//$arr_plugin_list=array();

//$arr_plugin_list['product'][]='attachments';

//Standard plugins. The user can create her plugins in other files.
class product_attachments extends Webmodel {

	function __construct()
	{

		parent::__construct("product_attachments");

	}
	
	function update($post, $conditions="")
	{
	
		$return_file=Webmodel::update($post, $conditions);
		
		if($return_file==1 && $_FILES['file']['name']!='')
		{
		
			if($post['file']!=$_FILES['file']['name'])
			{
			
				//Delete old photo...

				if(!unlink($this->components['file']->path.'/'.$post['file']))
				{
				
					//die;
				
				}
			
			}
			
		}


		return $return_file;
			
	}

	function delete($conditions="")
	{

		//Delete images from field...
		
		$query=$this->select($conditions, array('IdProduct_attachments', 'file', 'idproduct'));

		while(list($iattachment, $file, $idproduct)=$this->fetch_row($query))
		{
			
			if($file!='')
			{
				

				if(!unlink($this->components['file']->path.'/'.$file))
				{

					return 0;
					
				}
				

			}

		}

 		return MySQLClass::webtsys_query('delete from '.$this->name.' '.$conditions);
		
	}

}


Webmodel::$model['product_attachments']=new product_attachments();

Webmodel::$model['product_attachments']->components['name']=new CharField(255);
Webmodel::$model['product_attachments']->components['name']->required=1;

Webmodel::$model['product_attachments']->components['file']=new FileField('file', PhangoVar::$base_path.'/shop/product_attachments/images/', Routes::$root_url.'/shop/product_attachments/images', $type);
Webmodel::$model['product_attachments']->components['file']->required=1;

Webmodel::$model['product_attachments']->components['idproduct']=new ForeignKeyField(Webmodel::$model['product'], 11);
Webmodel::$model['product_attachments']->components['idproduct']->required=1;

//Paypal

Webmodel::$model['paypal_check']=new Webmodel('paypal_check');

Webmodel::$model['paypal_check']->register('cookie_shop', 'CharField', array(255), 1);
Webmodel::$model['paypal_check']->register('ckeck', 'BooleanField', array());

//Characteristics example plugin

Webmodel::$model['characteristic']=new Webmodel('characteristic');

Webmodel::$model['characteristic']->register('name', 'I18nField', array(new TextField()), 1);

//The type search in an array in config, can be for example TextForm or ColorForm with a color picker. The value always is text. TextForm is the key, the value is the explain text. If is an array with explain text and library path for load. I can use a table with 3 fields, form, name and path if need load the thing.

Webmodel::$model['characteristic']->register('type', 'CharField', array(255), 1);

//Children of characteristic

Webmodel::$model['characteristic_cat']=new Webmodel('characteristic_cat');

Webmodel::$model['characteristic_cat']->register('idcat', 'ForeignKeyField', array(Webmodel::$model['cat_product']), 1);

Webmodel::$model['characteristic_cat']->register('idcharacteristic', 'ForeignKeyField', array(Webmodel::$model['characteristic']), 1);

Webmodel::$model['characteristic_cat']->components['idcat']->name_field_to_field='title';
Webmodel::$model['characteristic_cat']->components['idcharacteristic']->name_field_to_field='name';

//Webmodel::$model['characteristic']->register('idproduct', 'ForeignKeyField', array('product'), 1);

Webmodel::$model['characteristic_standard_option']=new Webmodel('characteristic_standard_option');

Webmodel::$model['characteristic_standard_option']->register('name', 'I18nField', array(new TextField()), 1);

Webmodel::$model['characteristic_standard_option']->register('added_price', 'MoneyField', array(), 0);

//Webmodel::$model['characteristic_standard_option']->register('characteristic', 'ForeignKeyField', array('characteristic'), 1);

Webmodel::$model['characteristic_standard_option']->register('idcharacteristic', 'ForeignKeyField', array(Webmodel::$model['characteristic']), 1);

Webmodel::$model['characteristic_standard_option']->register('idproduct', 'ForeignKeyField', array(Webmodel::$model['product']), 0);

Webmodel::$model['characteristic_standard_option']->register('position', 'IntegerField', array(), 0);

//Webmodel::$model['characteristic_standard_option']->register('add', 'BooleanField', array(), 0);

Webmodel::$model['characteristic_standard_option']->register('option_delete', 'IntegerField', array(), 0);

//Options for product

/*Webmodel::$model['characteristic_option']=new Webmodel('characteristic_option');

Webmodel::$model['characteristic_option']->register('name', 'I18nField', array(new TextField()), 1);

Webmodel::$model['characteristic_option']->register('characteristic', 'ForeignKeyField', array('characteristic'), 1);

//If false, this option is deleted for standard, if is add, is added to this product. 

Webmodel::$model['characteristic_option']->register('add', 'BooleanField', array(), 0);

Webmodel::$model['characteristic_option']->register('idproduct', 'ForeignKeyField', array('product'), 1);*/

//Moneyfield

class MoneyField extends DoubleField{

	public function check($value)
	{
	
		$value=str_replace(',', '.', $value);
		
		return parent::check($value);
	
	}


	function show_formatted($value)
	{

		return $this->currency_format($value);

	}

	static function currency_format($value, $symbol_view=1)
	{

		//global $arr_currency, $arr_change_currency;
		
		if(isset($_SESSION['idcurrency']))
		{
		
			$idcurrency=$_SESSION['idcurrency'];
			
		}
		else
		{
		
			$idcurrency=ConfigShop::$config_shop['idcurrency'];
		
		}
		
		$symbol_currency=ConfigShop::$arr_currency[$idcurrency];
		
		if(ConfigShop::$config_shop['idcurrency']!=$idcurrency)
		{

			//Make conversion

			$change_value=@ConfigShop::$arr_change_currency[ConfigShop::$config_shop['idcurrency']][$idcurrency];

			if($change_value>0)
			{

				$value=$value*$change_value;

			}
			else
			{
				//Obtain $change_value for inverse arr_change_currency

				if( isset(ConfigShop::$arr_change_currency[$idcurrency][ConfigShop::$config_shop['idcurrency']]) )
				{

					/*$change_value=1/$arr_change_currency[$idcurrency][ ConfigShop::$config_shop['idcurrency'] ];
					$value=$value*$change_value;*/
					$value=$value/ConfigShop::$arr_change_currency[$idcurrency][ ConfigShop::$config_shop['idcurrency'] ];

				}
				else
				{

					$symbol_currency=ConfigShop::$arr_currency[ConfigShop::$config_shop['idcurrency']];

				}

			}
			

		}
		
		$value=round($value, 2, PHP_ROUND_HALF_UP);
		
		$arr_symbol[0]='';
		$arr_symbol[1]=' '.$symbol_currency;
		
		return number_format($value, 2).$arr_symbol[$symbol_view];

	}

}
/*
function load_plugin($hook_plugin)
{

	$arr_plugin=array();
		
	$query=Webmodel::$model['plugin_shop']->select('where element="'..'" order by position ASC', array('plugin'));
	
	while(list($plugin)=webtsys_fetch_row($query))
	{
	
		$func_plugin=ucfirst($plugin).'Show';
		
		$arr_plugin[$plugin]=$func_plugin;
	
	}
	
	$arr_product['plugins']=$arr_plugin;

}

*/

class PreparePluginClass {

	public $hook_plugin;
	public $plugin;
	public $arr_plugins;
	public $arr_plugin_list;
	public $arr_class_plugin;
	
	public function __construct($hook_plugin)
	{
	
		$this->hook_plugin=$hook_plugin;
		$this->arr_plugin_list=array();
		$this->arr_plugins=array();
		$this->arr_class_plugin=array();
	
	}
	
	public function obtain_list_plugins()
	{
	
		//$arr_plugin=array();
		
		$query=Webmodel::$model['plugin_shop']->select('where element="'.$this->hook_plugin.'" order by position ASC', array('plugin'));
		
		while(list($plugin)=Webmodel::$model['plugin_shop']->fetch_row($query))
		{
		
			$class_plugin=ucfirst($plugin).ucfirst($this->hook_plugin).'Class';
			
			$this->arr_plugins[$plugin]=$class_plugin;
			$this->arr_plugin_list[$plugin]=$plugin;
		
		}
	
	}
	
	public function load_plugin($plugin, $arguments=array())
	{
	
		Utils::load_libraries(array($plugin), PhangoVar::$base_path.'/modules/shop/plugins/'.$plugin.'/'.$this->hook_plugin.'/');
	
		$func_class=$this->arr_plugins[$plugin];
	
		$this->arr_class_plugin[$plugin]=new $func_class($arguments);
	
		//$this->arr_plugin_list[$plugin]=$plugin;
	
	}
	
	public function load_all_plugins($arguments=array())
	{
	
		foreach($this->arr_plugin_list as $plugin => $func_plugin)
		{
			
			$this->load_plugin($plugin);
		
		}
	
	}
	
	/*public function show_plugin($plugin, $idproduct)
	{
	
		$func_plugin=$this->arr_plugins[$plugin];
		
		load_libraries(array($plugin), $base_path.'modules/shop/plugins/'.$plugin.'/'.$this->hook_plugin.'/');
		
		return $func_plugin($idproduct);
	
	}
	
	public function show_all_plugins($iproduct)
	{
	
		foreach($this->arr_plugins as $plugin => $func_plugin)
		{
			
			echo $this->show_plugin($plugin, $idproduct);
		
		}
	
	}*/

}
/*
interface PluginClass {
	
	//Here prepare the config of the plugin.
	
	public function prepare_plugin();
	
	public function show();
	
	public function return_value_modified();

}
*/

class ConfigShop {

	static public $arr_fields_address=array('name', 'last_name', 'nif', 'address', 'city', 'region', 'country', 'zip_code', 'phone', 'fax');
	static public $arr_fields_transport=array('name_transport', 'last_name_transport', 'address_transport', 'city_transport', 'region_transport', 'country_transport', 'zip_code_transport', 'phone_transport');
	static public $num_address_transport=5;
	static public $config_shop=array();
	static public $arr_currency=array(); 
	static public $arr_change_currency=array();
	static public $arr_order=array();
	static public $arr_plugin_options=array();

}

class PaymentClass {

	//public $cart;

	function __construct()
	{
	
		//$this->cart=new CartClass();
	
	}
	
	public function checkout($cart)
	{
		return 1;
	}
	
	public function cancel_checkout()
	{
	
		
	
	}

}

$arr_module_insert['shop']=array('name' => 'shop', 'admin' => 1, 'admin_script' => array('shop', 'shop'), 'load_module' => '', 'app_index' => 1, 'yes_config' => 1);

$arr_module_sql['shop']='shop.sql';

$arr_module_remove['shop']=array('product', 'image_product', 'cat_product', 'taxes', 'transport', 'price_transport', 'zone_shop', 'country_shop', 'config_shop', 'address_transport', 'payment_form', 'cart_shop', 'order_shop', 'type_product_option', 'product_option', 'group_shop', 'group_shop_users', 'currency', 'currency_change');

?>

