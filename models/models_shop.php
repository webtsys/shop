<?php

load_libraries(array('i18n_fields', 'fields/moneyfield', 'fields/passwordfield', 'models/userphango'));

load_lang('shop');
load_lang('common');
load_lang('users');

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

				$this->components['other_countries']->std_error=PhangoVar::$lang['shop']['error_other_countries_is_selected'];

				return 0;

			}

		}
		
		return parent::update($post, $conditions);

	}
	
}

PhangoVar::$model['zone_shop']=new zone_shop();

PhangoVar::$model['zone_shop']->set_component('name', 'I18nField', array(new CharField(255)), 1);

/*foreach($arr_i18n as PhangoVar::$lang_field)
{

	PhangoVar::$model['zone_shop']->components['name_'.PhangoVar::$lang_field]=new CharField(255);
	PhangoVar::$model['zone_shop']->components['name_'.PhangoVar::$lang_field]->required=1;

}*/

PhangoVar::$model['zone_shop']->set_component('code', 'CharField', array(25), 1);

//Code 0 is for transport
//Code 1 is for taxes

PhangoVar::$model['zone_shop']->set_component('type', 'IntegerField', array(11));
PhangoVar::$model['zone_shop']->components['type']->form='HiddenForm';

PhangoVar::$model['zone_shop']->set_component('other_countries', 'BooleanField', array());

PhangoVar::$model['country_shop']=new country_shop();

/*foreach($arr_i18n as PhangoVar::$lang_field)
{

	PhangoVar::$model['country_shop']->components['name_'.PhangoVar::$lang_field]=new CharField(255);
	PhangoVar::$model['country_shop']->components['name_'.PhangoVar::$lang_field]->required=1;

}*/

PhangoVar::$model['country_shop']->set_component('name', 'I18nField', array(new CharField(255)) , 1);

SlugifyField::add_slugify_i18n_fields('country_shop', 'name');

PhangoVar::$model['country_shop']->set_component('code', 'CharField', array(25), 1);

//PhangoVar::$model['country_shop']->components['idzone_taxes']=new ForeignKeyField('zone_shop');
PhangoVar::$model['country_shop']->set_component('idzone_transport', 'ForeignKeyField', array('zone_shop'));

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

PhangoVar::$model['user_shop']=new UserPhangoModel('user_shop');

//PhangoVar::$model['user_shop']=new user_shop('user_shop');

PhangoVar::$model['user_shop']->set_component('email', 'CharField', array(255), 1);

PhangoVar::$model['user_shop']->set_component('password', 'PasswordField', array(255), 1);

PhangoVar::$model['user_shop']->set_component('token_client', 'CharField', array(255), 1);

PhangoVar::$model['user_shop']->set_component('token_recovery', 'CharField', array(255), 1);

PhangoVar::$model['user_shop']->set_component('name', 'CharField', array(255), 1);
PhangoVar::$model['user_shop']->set_component('last_name', 'CharField', array(255), 1);
PhangoVar::$model['user_shop']->set_component('address', 'CharField', array(255), 1);
PhangoVar::$model['user_shop']->set_component('zip_code', 'CharField', array(255), 1);
PhangoVar::$model['user_shop']->set_component('region', 'CharField', array(255), 1);
PhangoVar::$model['user_shop']->set_component('city', 'CharField', array(255), 1);
PhangoVar::$model['user_shop']->set_component('country', 'ForeignKeyField', array('country_shop'), 1);
PhangoVar::$model['user_shop']->set_component('phone', 'CharField', array(255), 1);//Only for special effects...
PhangoVar::$model['user_shop']->set_component('fax', 'CharField', array(255));//Only for special effects...
PhangoVar::$model['user_shop']->set_component('nif', 'CharField', array(255), 1);//Only for special effects...
PhangoVar::$model['user_shop']->set_component('enterprise_name', 'CharField', array(255));//Only for special effects...
PhangoVar::$model['user_shop']->set_component('last_connection', 'IntegerField', array(11));
PhangoVar::$model['user_shop']->set_component('format_date', 'ChoiceField', array(10, 'string', array('d-m-Y', 'Y-m-d')));
PhangoVar::$model['user_shop']->set_component('format_time', 'IntegerField', array(11));
PhangoVar::$model['user_shop']->set_component('timezone', 'ChoiceField', array(35, 'string', array(), MY_TIMEZONE));
PhangoVar::$model['user_shop']->set_component('ampm', 'ChoiceField', array(10, 'string', array('H:i:s', 'h:i:s A'), MY_TIMEZONE));

PhangoVar::$model['user_shop']->set_component('disabled', 'BooleanField', array());

PhangoVar::$model['country_user_shop']=new Webmodel('country_user_shop');

PhangoVar::$model['country_user_shop']->components['iduser']=new IntegerField(11);
PhangoVar::$model['country_user_shop']->components['idcountry']=new IntegerField(11);

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
		
			$idproduct=webtsys_insert_id();
		
			if( PhangoVar::$model['product_relationship']->insert(array('idproduct' => $idproduct, 'idcat_product' => $_GET['idcat'])) )
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
		
		while(list($idproduct)=webtsys_fetch_row($query))
		{
		
			$arr_id_prod[]=$idproduct;
		
		}
		
		PhangoVar::$model['image_product']->delete('where image_product.idproduct IN ('.implode(', ', $arr_id_prod).')');
		
		PhangoVar::$model['product_relationship']->delete('where product_relationship.idproduct IN ('.implode(', ', $arr_id_prod).')');
		
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

PhangoVar::$model['product']=new product();

PhangoVar::$model['product']->set_component('referer', 'CharField', array(255), 1);

PhangoVar::$model['product']->set_component('title', 'I18nField', array(new CharField(255)), 1);

SlugifyField::add_slugify_i18n_fields('product', 'title');

PhangoVar::$model['product']->set_component('description', 'I18nField', array(new TextHTMLField()), 1);

PhangoVar::$model['product']->set_component('description_short', 'I18nField', array(new CharField(1000)));

//PhangoVar::$model['product']->components['idcat']=new ForeignKeyField('cat_product', 11);

//PhangoVar::$model['product']->components['idcat']->required=1;

PhangoVar::$model['product']->set_component('price', 'MoneyField', array());

PhangoVar::$model['product']->set_component('special_offer', 'DoubleField', array());

PhangoVar::$model['product']->set_component('stock', 'BooleanField', array());

PhangoVar::$model['product']->set_component('date', 'DateField', array());

PhangoVar::$model['product']->set_component('about_order', 'BooleanField', array());

//PhangoVar::$model['product']->components['extra_options']=new ChoiceField(255, 'string');

PhangoVar::$model['product']->set_component('weight', 'DoubleField', array());

PhangoVar::$model['product']->set_component('num_sold', 'IntegerField', array());

PhangoVar::$model['product']->set_component('cool', 'BooleanField', array());

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

				$query=webtsys_query('update image_product set principal=0 where idproduct='.$post['idproduct']);

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

				$query=webtsys_query('update image_product set principal=0 where idproduct='.$post['idproduct']);

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

		while(list($idimage, $principal, $photo, $idproduct)=webtsys_fetch_row($query))
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
				
				$query2=webtsys_query('update image_product set principal=1 where idproduct='.$idproduct.' and IdImage_product!='.$idimage.' limit 1');

			}

		}

 		return webtsys_query('delete from '.$this->name.' '.$conditions);
		
	}

}

PhangoVar::$model['image_product']=new image_product();

PhangoVar::$model['image_product']->set_component('principal', 'BooleanField', array());
PhangoVar::$model['image_product']->set_component('photo', 'ImageField', array('photo', PhangoVar::$media_path.'/modules/shop/media/images/products/', get_base_url_media('shop', 'images/products'), 'image', 1, array('small' => 45, 'mini' => 150, 'medium' => 300, 'preview' => 600)), 1);
PhangoVar::$model['image_product']->set_component('idproduct', 'ForeignKeyField', array('product', 11), 1);


class cat_product extends Webmodel {

	function __construct()
	{

		parent::__construct("cat_product");

	}	
	
	function delete($conditions="")
	{
		
		$query=$this->select($conditions, array('IdCat_product'));
		
		while(list($idcat_product)=webtsys_fetch_row($query))
		{
		
			$query=PhangoVar::$model['product']->delete('where idcat='.$idcat_product);
		
		}
	
		return parent::delete($conditions);
	
	}

}

PhangoVar::$model['cat_product']=new cat_product();

$field_title_cat=new TextHTMLField();

PhangoVar::$model['cat_product']->set_component('title', 'I18nField', array($field_title_cat), 1);

PhangoVar::$model['cat_product']->set_component('subcat', 'ParentField', array('cat_product', 255));

PhangoVar::$model['cat_product']->set_component('description', 'I18nField', array(new TextHTMLField()) , 1);

PhangoVar::$model['cat_product']->set_component('view_only_mode', 'BooleanField', array());

PhangoVar::$model['cat_product']->set_component('position', 'IntegerField', array());

PhangoVar::$model['cat_product']->set_component('image_cat', 'ImageField', array('image_cat', PhangoVar::$application_path.'media/shop/images/products/', PhangoVar::$base_url.'/media/shop/images/products', 'image', 0) );

class product_relationship extends Webmodel {

	function insert($post)
	{
		
		if( !$this->select_count('where idproduct='.$post['idproduct'].' and idcat_product='.$post['idcat_product'], 'IdProduct_relationship') )
		{
		
			return parent::insert($post);
		
		}
		else
		{
			$this->std_error=PhangoVar::$lang['shop']['product_is_already_on_category'];
		
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
			$this->std_error=PhangoVar::$lang['shop']['product_is_already_on_category'];
		
			return false;
		
		}
	
	}
	
}

PhangoVar::$model['product_relationship']=new product_relationship('product_relationship');

PhangoVar::$model['product_relationship']->set_component('idproduct', 'ForeignKeyField', array('product', 11), 1);

PhangoVar::$model['product_relationship']->set_component('idcat_product', 'ForeignKeyField', array('cat_product', 11), 1);
PhangoVar::$model['product_relationship']->components['idcat_product']->name_field_to_field='title';


class taxes extends Webmodel {

	function __construct()
	{

		parent::__construct("taxes");

	}	
	
}

PhangoVar::$model['taxes']=new taxes();

PhangoVar::$model['taxes']->components['name']=new CharField(255);
PhangoVar::$model['taxes']->components['name']->required=1;

PhangoVar::$model['taxes']->components['percent']=new DoubleField(255);

PhangoVar::$model['taxes']->components['country']=new ForeignKeyField('zone_shop');
PhangoVar::$model['taxes']->components['country']->required=1;

class transport extends Webmodel {

	function __construct()
	{

		parent::__construct("transport");

	}	
	
}

PhangoVar::$model['transport']=new transport();

PhangoVar::$model['transport']->set_component('name', 'CharField', array(255), 1);

PhangoVar::$model['transport']->set_component('country', 'ForeignKeyField', array('zone_shop'), 1);

PhangoVar::$model['transport']->set_component('type', 'ChoiceField', array($size=11, $type='integer', $arr_values=array(0, 1), $default_value=0));

class price_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport");

	}	
	
}

PhangoVar::$model['price_transport']=new price_transport();

PhangoVar::$model['price_transport']->components['price']=new MoneyField();
PhangoVar::$model['price_transport']->components['price']->required=1;

PhangoVar::$model['price_transport']->components['weight']=new DoubleField();
PhangoVar::$model['price_transport']->components['weight']->required=0;

PhangoVar::$model['price_transport']->components['idtransport']=new ForeignKeyField('transport');
PhangoVar::$model['price_transport']->components['idtransport']->form='HiddenForm';
PhangoVar::$model['price_transport']->components['idtransport']->required=1;

class price_transport_price extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport_price");

	}	
	
}

PhangoVar::$model['price_transport_price']=new price_transport_price();

PhangoVar::$model['price_transport_price']->components['price']=new MoneyField();
PhangoVar::$model['price_transport_price']->components['price']->required=0;

PhangoVar::$model['price_transport_price']->components['min_price']=new MoneyField();
PhangoVar::$model['price_transport_price']->components['min_price']->required=0;

PhangoVar::$model['price_transport_price']->components['idtransport']=new ForeignKeyField('transport');
PhangoVar::$model['price_transport_price']->components['idtransport']->form='HiddenForm';
PhangoVar::$model['price_transport_price']->components['idtransport']->required=1;


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

		$query=webtsys_query('ALTER TABLE order_shop AUTO_INCREMENT = '.$_POST['num_begin_bill']);
		

		return Webmodel::update($post, $conditions);

	}
	
}

PhangoVar::$model['config_shop']=new config_shop();

PhangoVar::$model['config_shop']->set_component('num_news', 'IntegerField', array(11));

/*PhangoVar::$model['config_shop']->components['num_news']=new IntegerField(11);
PhangoVar::$model['config_shop']->components['num_news']->required=1;*/
/*PhangoVar::$model['config_shop']->components['yes_taxes']=new BooleanField();*/
$field_conditions=new TextHTMLField();
PhangoVar::$model['config_shop']->components['conditions']=new I18nField($field_conditions);
//create_field_multilang('config_shop', 'conditions', $field_conditions, 0);
PhangoVar::$model['config_shop']->components['no_transport']=new BooleanField();
//PhangoVar::$model['config_shop']->components['type_index']=new CharField(25);
//PhangoVar::$model['config_shop']->components['ssl_url']=new BooleanField();

$field_title_shop=new TextHTMLField();
PhangoVar::$model['config_shop']->components['title_shop']=new I18nField($field_title_shop);
//create_field_multilang('config_shop', 'title_shop', $field_title_shop, 0);*/
//PhangoVar::$model['config_shop']->components['description_shop']->multilang=1;
$field_description_shop=new TextHTMLField();
PhangoVar::$model['config_shop']->components['description_shop']=new I18nField($field_description_shop);
//create_field_multilang('config_shop', 'description_shop', $field_description_shop, 0);
//PhangoVar::$model['config_shop']->components['cart_style']=new IntegerField(11);
//PhangoVar::$model['config_shop']->components['idtax']=new ForeignKeyField('taxes', 11);
PhangoVar::$model['config_shop']->components['head_bill']=new CharField(255);
PhangoVar::$model['config_shop']->components['num_begin_bill']=new IntegerField(11);
PhangoVar::$model['config_shop']->components['elements_num_bill']=new IntegerField(11);
PhangoVar::$model['config_shop']->components['image_bill']=new ImageField('image_bill', PhangoVar::$application_path.'media/shop/images/products/', PhangoVar::$media_url.'/media/shop/images/products', 'image', 0);

PhangoVar::$model['config_shop']->components['bill_data_shop']=new TextField();
PhangoVar::$model['config_shop']->components['bill_data_shop']->form='TextAreaForm';
PhangoVar::$model['config_shop']->components['bill_data_shop']->br=0;
PhangoVar::$model['config_shop']->components['footer_bill']=new TextField();
PhangoVar::$model['config_shop']->components['footer_bill']->form='TextAreaForm';
PhangoVar::$model['config_shop']->components['footer_bill']->br=0;

/*PhangoVar::$model['config_shop']->components['explain_discounts_page']=new ForeignKeyField('page', 11);
PhangoVar::$model['config_shop']->components['explain_discounts_page']->container_model='pages';*/

PhangoVar::$model['config_shop']->components['idcurrency']=new ForeignKeyField('currency', 11);
PhangoVar::$model['config_shop']->components['idcurrency']->required=1;

PhangoVar::$model['config_shop']->components['view_only_mode']=new BooleanField();

class address_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("address_transport");

	}	
	
}

PhangoVar::$model['address_transport']=new address_transport();

PhangoVar::$model['address_transport']->set_component('iduser', 'ForeignKeyField', array('user_shop'), 1);
PhangoVar::$model['address_transport']->set_component('name_transport', 'CharField', array(255), 1);
PhangoVar::$model['address_transport']->set_component('last_name_transport', 'CharField', array(255), 1);
PhangoVar::$model['address_transport']->set_component('enterprise_name_transport', 'CharField', array(255));
PhangoVar::$model['address_transport']->set_component('address_transport', 'CharField', array(255), 1);
PhangoVar::$model['address_transport']->set_component('zip_code_transport', 'CharField', array(255), 1);
PhangoVar::$model['address_transport']->set_component('phone_transport', 'CharField', array(255), 1);
PhangoVar::$model['address_transport']->set_component('city_transport', 'CharField', array(255), 1);
PhangoVar::$model['address_transport']->set_component('region_transport', 'CharField', array(255), 1);
PhangoVar::$model['address_transport']->set_component('country_transport', 'ForeignKeyField', array('country_shop', 11), 1);
//PhangoVar::$model['address_transport']->set_component('zone_transport', 'ForeignKeyField', array('zone_shop', 11));

class payment_form extends Webmodel {

	function __construct()
	{

		parent::__construct("payment_form");

	}	
	
}

PhangoVar::$model['payment_form']=new payment_form();
PhangoVar::$model['payment_form']->set_component('name', 'I18nField', array(new TextField()) , 1);
PhangoVar::$model['payment_form']->set_component('code', 'ChoiceField', array(255, 'string'));
PhangoVar::$model['payment_form']->set_component('price_payment', 'MoneyField', array() );

class cart_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("cart_shop");

	}	
	
}

PhangoVar::$model['cart_shop']=new cart_shop();
PhangoVar::$model['cart_shop']->set_component('token', 'CharField', array(255));
PhangoVar::$model['cart_shop']->set_component('idproduct', 'ForeignKeyField', array('product', 11));
PhangoVar::$model['cart_shop']->components['idproduct']->fields_related_model=array('referer', 'title');
PhangoVar::$model['cart_shop']->set_component('price_product', 'MoneyField', array());
/*PhangoVar::$model['cart_shop']->components['name_taxes_product']=new DoubleField();
PhangoVar::$model['cart_shop']->components['taxes_product']=new DoubleField();*/
PhangoVar::$model['cart_shop']->set_component('units', 'IntegerField', array());
PhangoVar::$model['cart_shop']->set_component('details', 'ArrayField', array(new CharField(255)));
PhangoVar::$model['cart_shop']->set_component('alter_price_elements', 'ArrayField', array(new MoneyField()));
PhangoVar::$model['cart_shop']->set_component('time', 'IntegerField', array());
PhangoVar::$model['cart_shop']->set_component('weight', 'DoubleField', array());

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
					
					PhangoVar::$model['invoice_num']->insert(array('token_shop' => $token));
					
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

PhangoVar::$model['order_shop']=new order_shop();

PhangoVar::$model['order_shop']->components['token']=new CharField(255);
PhangoVar::$model['order_shop']->components['referer']=new CharField(255);
PhangoVar::$model['order_shop']->components['name']=new CharField(255);
PhangoVar::$model['order_shop']->components['last_name']=new CharField(255);
PhangoVar::$model['order_shop']->components['enterprise_name']=new CharField(255);
PhangoVar::$model['order_shop']->components['email']=new CharField(255);
PhangoVar::$model['order_shop']->components['nif']=new CharField(255);
PhangoVar::$model['order_shop']->components['address']=new CharField(255);
PhangoVar::$model['order_shop']->components['zip_code']=new CharField(255);
PhangoVar::$model['order_shop']->components['city']=new CharField(255);
PhangoVar::$model['order_shop']->components['region']=new CharField(255);
PhangoVar::$model['order_shop']->components['country']=new I18nField(new TextField());
PhangoVar::$model['order_shop']->components['phone']=new CharField(255);
PhangoVar::$model['order_shop']->components['fax']=new CharField(255);

PhangoVar::$model['order_shop']->components['name_transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['last_name_transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['enterprise_name_transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['address_transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['zip_code_transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['city_transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['region_transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['country_transport']=new I18nField(new TextField());
PhangoVar::$model['order_shop']->components['phone_transport']=new CharField(255);
//PhangoVar::$model['order_shop']->components['zone_transport']=new IntegerField(11);

PhangoVar::$model['order_shop']->components['transport']=new CharField(255);
PhangoVar::$model['order_shop']->components['price_transport']=new MoneyField();

PhangoVar::$model['order_shop']->components['name_payment']=new CharField(255);
PhangoVar::$model['order_shop']->components['price_payment']=new MoneyField();

PhangoVar::$model['order_shop']->components['make_payment']=new BooleanField();

PhangoVar::$model['order_shop']->components['observations']=new TextHTMLField();

PhangoVar::$model['order_shop']->components['date_order']=new DateField();

//PhangoVar::$model['order_shop']->components['iduser']=new ForeignKeyField('user_shop');

//PhangoVar::$model['order_shop']->components['payment_discount_percent']=new PercentField();

PhangoVar::$model['order_shop']->components['total_price']=new MoneyField();

/*PhangoVar::$model['order_shop']->components['invoice_num']=new ForeignKeyField('invoice_num');
PhangoVar::$model['order_shop']->components['invoice_num']->name_field_to_field='invoice_num';
*/

PhangoVar::$model['order_shop']->components['name']->required=1;	
PhangoVar::$model['order_shop']->components['last_name']->required=1;
PhangoVar::$model['order_shop']->components['email']->required=1;
PhangoVar::$model['order_shop']->components['address']->required=1;
PhangoVar::$model['order_shop']->components['zip_code']->required=1;
PhangoVar::$model['order_shop']->components['city']->required=1;
PhangoVar::$model['order_shop']->components['region']->required=1;
PhangoVar::$model['order_shop']->components['country']->required=1;
PhangoVar::$model['order_shop']->components['phone']->required=1;

PhangoVar::$model['order_shop']->components['name_transport']->required=1;	
PhangoVar::$model['order_shop']->components['last_name_transport']->required=1;
PhangoVar::$model['order_shop']->components['address_transport']->required=1;
PhangoVar::$model['order_shop']->components['zip_code_transport']->required=1;
PhangoVar::$model['order_shop']->components['city_transport']->required=1;
PhangoVar::$model['order_shop']->components['region_transport']->required=1;
PhangoVar::$model['order_shop']->components['country_transport']->required=1;
//PhangoVar::$model['order_shop']->components['zone_transport']->required=1;
PhangoVar::$model['order_shop']->components['phone_transport']->required=1;

PhangoVar::$model['order_shop']->components['token']->required=1;
PhangoVar::$model['order_shop']->components['transport']->required=1;
PhangoVar::$model['order_shop']->components['name_payment']->required=1;
PhangoVar::$model['order_shop']->components['price_payment']->required=1;

PhangoVar::$model['order_shop']->create_form();

PhangoVar::$model['order_shop']->forms['referer']->label=PhangoVar::$lang['shop']['referer'];
PhangoVar::$model['order_shop']->forms['name']->label=PhangoVar::$lang['users']['name'];
PhangoVar::$model['order_shop']->forms['last_name']->label=PhangoVar::$lang['users']['last_name'];
PhangoVar::$model['order_shop']->forms['enterprise_name']->label=PhangoVar::$lang['users']['enterprise_name'];
PhangoVar::$model['order_shop']->forms['email']->label=PhangoVar::$lang['users']['email'];
PhangoVar::$model['order_shop']->forms['nif']->label=PhangoVar::$lang['users']['nif'];
PhangoVar::$model['order_shop']->forms['address']->label=PhangoVar::$lang['common']['address'];
PhangoVar::$model['order_shop']->forms['zip_code']->label=PhangoVar::$lang['users']['zip_code'];
PhangoVar::$model['order_shop']->forms['city']->label=PhangoVar::$lang['users']['city'];
PhangoVar::$model['order_shop']->forms['region']->label=PhangoVar::$lang['common']['region'];
PhangoVar::$model['order_shop']->forms['country']->label=PhangoVar::$lang['common']['country'];
PhangoVar::$model['order_shop']->forms['phone']->label=PhangoVar::$lang['common']['phone'];
PhangoVar::$model['order_shop']->forms['fax']->label=PhangoVar::$lang['common']['fax'];
PhangoVar::$model['order_shop']->forms['name_transport']->label=PhangoVar::$lang['users']['name'];
PhangoVar::$model['order_shop']->forms['last_name_transport']->label=PhangoVar::$lang['users']['last_name'];
PhangoVar::$model['order_shop']->forms['enterprise_name_transport']->label=PhangoVar::$lang['users']['enterprise_name'];
PhangoVar::$model['order_shop']->forms['address_transport']->label=PhangoVar::$lang['common']['address'];
PhangoVar::$model['order_shop']->forms['zip_code_transport']->label=PhangoVar::$lang['common']['zip_code'];
PhangoVar::$model['order_shop']->forms['city_transport']->label=PhangoVar::$lang['common']['city'];
PhangoVar::$model['order_shop']->forms['region_transport']->label=PhangoVar::$lang['common']['region'];
PhangoVar::$model['order_shop']->forms['country_transport']->label=PhangoVar::$lang['common']['country'];
PhangoVar::$model['order_shop']->forms['phone_transport']->label=PhangoVar::$lang['common']['phone'];
//PhangoVar::$model['order_shop']->forms['zone_transport']->label=PhangoVar::$lang['shop']['zone'];
PhangoVar::$model['order_shop']->forms['transport']->label=PhangoVar::$lang['shop']['transport'];
PhangoVar::$model['order_shop']->forms['make_payment']->label=PhangoVar::$lang['shop']['make_payment'];
PhangoVar::$model['order_shop']->forms['observations']->label=PhangoVar::$lang['shop']['observations'];
PhangoVar::$model['order_shop']->forms['date_order']->label=PhangoVar::$lang['common']['date'];
//PhangoVar::$model['order_shop']->forms['invoice_num']->label=PhangoVar::$lang['shop']['invoice_num'];

PhangoVar::$model['order_shop_plugins']=new Webmodel('order_shop_plugins');

PhangoVar::$model['order_shop_plugins']->set_component('idorder_shop', 'ForeignKeyField', array('order_shop'));
PhangoVar::$model['order_shop_plugins']->set_component('name', 'I18nField', array('order_shop'));
PhangoVar::$model['order_shop_plugins']->set_component('add_price', 'MoneyField', array());
PhangoVar::$model['order_shop_plugins']->set_component('idcart_shop', 'ForeignKeyField', array('cart_shop'));

PhangoVar::$model['invoice_num']=new Webmodel('invoice_num');

PhangoVar::$model['invoice_num']->change_id_default('invoice_num');

PhangoVar::$model['invoice_num']->components['token_shop']=new CharField(255);

class type_product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("type_product_option");

	}	
	
}

PhangoVar::$model['type_product_option']=new type_product_option();

PhangoVar::$model['type_product_option']->components['title']=new I18nField(new TextField());
PhangoVar::$model['type_product_option']->components['title']->required=1;

PhangoVar::$model['type_product_option']->components['description']=new I18nField(new TextField());
PhangoVar::$model['type_product_option']->components['description']->required=1;

PhangoVar::$model['type_product_option']->components['question']=new I18nField(new TextField());
PhangoVar::$model['type_product_option']->components['question']->required=1;

PhangoVar::$model['type_product_option']->components['options']=new I18nField(new TextField());
PhangoVar::$model['type_product_option']->components['options']->required=0;

PhangoVar::$model['type_product_option']->components['price']=new MoneyField();
PhangoVar::$model['type_product_option']->components['price']->required=0;


class product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("product_option");

	}	
	
}

PhangoVar::$model['product_option']=new product_option();

PhangoVar::$model['product_option']->components['idtype']=new ForeignKeyField('type_product_option', 11);

PhangoVar::$model['product_option']->components['idtype']->required=1;

PhangoVar::$model['product_option']->components['idtype']->fields_related_model=array('title');
PhangoVar::$model['product_option']->components['idtype']->name_field_to_field='title';

PhangoVar::$model['product_option']->components['idproduct']=new ForeignKeyField('product', 11);

PhangoVar::$model['product_option']->components['idproduct']->form='HiddenForm';

PhangoVar::$model['product_option']->components['idproduct']->required=1;

PhangoVar::$model['product_option']->components['field_required']=new BooleanField();


class group_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop");

	}	
	
}

PhangoVar::$model['group_shop']=new group_shop();

PhangoVar::$model['group_shop']->components['name']=new I18nField(new CharField(255));
PhangoVar::$model['group_shop']->components['name']->required=1;
PhangoVar::$model['group_shop']->components['discount']=new PercentField(11);
//PhangoVar::$model['group_shop']->components['taxes_for_group']=new PercentField(11);
PhangoVar::$model['group_shop']->components['transport_for_group']=new PercentField(11);
PhangoVar::$model['group_shop']->components['shipping_costs_for_group']=new PercentField(11);

class group_shop_users extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop_users");

	}	
	
}

PhangoVar::$model['group_shop_users']=new group_shop_users();

PhangoVar::$model['group_shop_users']->components['iduser']=new ForeignKeyField('user', 11);
PhangoVar::$model['group_shop_users']->components['iduser']->required=1;
PhangoVar::$model['group_shop_users']->components['iduser']->fields_related_model=array('private_nick');
PhangoVar::$model['group_shop_users']->components['iduser']->name_field_to_field='private_nick';
PhangoVar::$model['group_shop_users']->components['group_shop']=new ForeignKeyField('group_shop', 11);
PhangoVar::$model['group_shop_users']->components['group_shop']->form='HiddenForm';
PhangoVar::$model['group_shop_users']->components['group_shop']->required=1;
PhangoVar::$model['group_shop_users']->components['group_shop']->container_model='shop';

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

		while(list($idcurrency)=webtsys_fetch_row($query))
		{

			$arr_id[]=$idcurrency;

			if(ConfigShop::$config_shop['idcurrency']==$idcurrency)
			{

				return 0;

			}

		}

		$query=PhangoVar::$model['currency_change']->delete('where idcurrency IN ('.implode(', ', $arr_id).') or idcurrency_change IN ('.implode(', ', $arr_id).')');

		return parent::delete($conditions);

	}

}

PhangoVar::$model['currency']=new currency('currency');

PhangoVar::$model['currency']->set_component('name', 'I18nField', array(new TextField()) , 1);

PhangoVar::$model['currency']->set_component('symbol', 'CharField', array(25), 1);

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

			$this->std_error=PhangoVar::$lang['shop']['this_currency_have_equivalence'];
			return 0;

		}

	}

	function update($post, $conditions='')
	{

		//We need see if exists the row with this data

		settype($post['idcurrency_related'], 'integer');

		$query=$this->select('where idcurrency_related='.$post['idcurrency_related'].' and idcurrency='.$post['idcurrency'], array('IdCurrency_change'));

		list($idcurrency_change)=webtsys_fetch_row($query);

		settype($idcurrency_change, 'integer');

		//Well, use $idcurrency_change how conditions...

		$conditions='where IdCurrency_change='.$idcurrency_change;

		return parent::update($post, $conditions);

	}
	
}

PhangoVar::$model['currency_change']=new currency_change('currency_change');

PhangoVar::$model['currency_change']->set_component('idcurrency', 'ParentField', array('currency', 11), 1);

PhangoVar::$model['currency_change']->set_component('idcurrency_related', 'ForeignKeyField', array('currency', 11), 1);
PhangoVar::$model['currency_change']->components['idcurrency_related']->name_field_to_field='name';
//PhangoVar::$model['currency_change']->components['idcurrency_related']->fields_related_model=array('name');

PhangoVar::$model['currency_change']->set_component('change_value', 'MoneyField', array(), 1);

//Class plugin_shop

PhangoVar::$model['plugin_shop']=new Webmodel('plugin_shop');

PhangoVar::$model['plugin_shop']->set_component('name', 'CharField', array(255), 1);

PhangoVar::$model['plugin_shop']->set_component('element', 'ChoiceField', array($size=255, $type='string', $arr_values=array('product', 'cart', 'discounts'), $default_value=''), 1);

PhangoVar::$model['plugin_shop']->set_component('plugin', 'ChoiceField', array($size=255, $type='string', $arr_values=array(''), $default_value=''), 1);

PhangoVar::$model['plugin_shop']->set_component('position', 'IntegerField', array() );

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

		while(list($iattachment, $file, $idproduct)=webtsys_fetch_row($query))
		{
			
			if($file!='')
			{
				

				if(!unlink($this->components['file']->path.'/'.$file))
				{

					return 0;
					
				}
				

			}

		}

 		return webtsys_query('delete from '.$this->name.' '.$conditions);
		
	}

}


PhangoVar::$model['product_attachments']=new product_attachments();

PhangoVar::$model['product_attachments']->components['name']=new CharField(255);
PhangoVar::$model['product_attachments']->components['name']->required=1;

PhangoVar::$model['product_attachments']->components['file']=new FileField('file', PhangoVar::$application_path.'media/shop/files/', PhangoVar::$base_url.'/media/shop/files', $type);
PhangoVar::$model['product_attachments']->components['file']->required=1;

PhangoVar::$model['product_attachments']->components['idproduct']=new ForeignKeyField('product', 11);
PhangoVar::$model['product_attachments']->components['idproduct']->required=1;

class MoneyField extends DoubleField{


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
		
	$query=PhangoVar::$model['plugin_shop']->select('where element="'..'" order by position ASC', array('plugin'));
	
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
		
		$query=PhangoVar::$model['plugin_shop']->select('where element="'.$this->hook_plugin.'" order by position ASC', array('plugin'));
		
		while(list($plugin)=webtsys_fetch_row($query))
		{
		
			$class_plugin=ucfirst($plugin).ucfirst($this->hook_plugin).'Class';
			
			$this->arr_plugin[$plugin]=$class_plugin;
		
		}
	
	}
	
	public function load_plugin($plugin, $arguments=array())
	{
	
		load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/'.$plugin.'/'.$this->hook_plugin.'/');
	
		$func_class=$this->arr_plugins[$plugin];
	
		$this->arr_class_plugin[$plugin]=new $func_class($arguments);
	
		$this->arr_plugin_list[$plugin]=$plugin;
	
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

class PluginClass {

	public function __construct()
	{
	
	}
	
	//Here prepare the config of the plugin.
	
	public function prepare_plugin()
	{
	
	}
	
	public function show()
	{
	
	}
	
	public function return_value_modified()
	{
	
	}

}

class ConfigShop {

	static public $arr_fields_address=array('name', 'last_name', 'nif', 'address', 'city', 'region', 'country', 'zip_code', 'phone', 'fax');
	static public $arr_fields_transport=array('name_transport', 'last_name_transport', 'address_transport', 'city_transport', 'region_transport', 'country_transport', 'zip_code_transport', 'phone_transport');
	static public $num_address_transport=5;
	static public $config_shop=array();
	static public $arr_currency=array(); 
	static public $arr_change_currency=array();

}

class PaymentClass {

	public $cart;

	function __construct()
	{
	
		$this->cart=new CartClass();
	
	}
	
	public function checkout()
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

