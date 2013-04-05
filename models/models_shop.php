<?php
global $arr_i18n, $base_url, $arr_plugin_list;

load_libraries(array('i18n_fields', 'fields/moneyfield'));

load_lang('shop');
load_lang('common');
load_lang('user');

class product extends Webmodel {

	function __construct()
	{

		parent::__construct("product");

	}
	
	function insert($post)
	{
	
		global $model;
	
		$post=$this->components['title']->add_slugify_i18n_post('title', $post);
	
		if(parent::insert($post))
		{
		
			settype($_GET['idcat'], 'integer');
		
			$idproduct=webtsys_insert_id();
		
			if( $model['product_relationship']->insert(array('idproduct' => $idproduct, 'idcat_product' => $_GET['idcat'])) )
			{
				return true;
			}
			
			return false;
		
		}
		else
		{
		
			return false;
		
		}
	
	}
	
	function update($post, $conditions='')
	{
	
		$post=$this->components['title']->add_slugify_i18n_post('title', $post);
	
		return parent::update($post, $conditions);
	
	}
	
	function delete($conditions="")
	{
	
		global $model;
	
		//Obtain ids for this product for delete images of product.
		
		$query=$this->select($conditions, array('IdProduct'));
		
		$arr_id_prod=array(0);
		
		while(list($idproduct)=webtsys_fetch_row($query))
		{
		
			$arr_id_prod[]=$idproduct;
		
		}
		
		$model['image_product']->delete('where image_product.idproduct IN ('.implode(', ', $arr_id_prod).')');
	
		return parent::delete($conditions);
	
	}
	
}

$model['product']=new product();

$model['product']->components['referer']=new CharField(255);

$model['product']->components['referer']->required=1;

$model['product']->components['title']=new I18nField(new CharField(255));

$model['product']->components['title']->required=1;

SlugifyField::add_slugify_i18n_fields('product', 'title');

$model['product']->components['description']=new I18nField(new TextHTMLField());

$model['product']->components['description']->required=1;

$model['product']->components['description_short']=new I18nField(new CharField(1000));

$model['product']->components['description_short']->required=0;

//$model['product']->components['idcat']=new ForeignKeyField('cat_product', 11);

//$model['product']->components['idcat']->required=1;

$model['product']->components['price']=new MoneyField();

$model['product']->components['special_offer']=new DoubleField();

$model['product']->components['stock']=new BooleanField();

$model['product']->components['date']=new DateField();

$model['product']->components['about_order']=new BooleanField();

$model['product']->components['extra_options']=new ChoiceField(255, 'string');

$model['product']->components['weight']=new DoubleField();

$model['product']->components['num_sold']=new IntegerField();

$model['product']->components['cool']=new BooleanField();

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

$model['image_product']=new image_product();

$model['image_product']->components['principal']=new BooleanField();
$model['image_product']->components['photo']=new ImageField('photo', $base_path.'application/media/shop/images/products/', $base_url.'/media/shop/images/products', 'image', 1, array('small' => 45, 'mini' => 150, 'medium' => 300, 'preview' => 600));
$model['image_product']->components['photo']->required=1;
$model['image_product']->components['idproduct']=new ForeignKeyField('product', 11);
$model['image_product']->components['idproduct']->required=1;


class cat_product extends Webmodel {

	function __construct()
	{

		parent::__construct("cat_product");

	}	
	
	function delete($conditions="")
	{
	
		global $model;
		
		$query=$this->select($conditions, array('IdCat_product'));
		
		while(list($idcat_product)=webtsys_fetch_row($query))
		{
		
			$query=$model['product']->delete('where idcat='.$idcat_product);
		
		}
	
		return parent::delete($conditions);
	
	}

}

$model['cat_product']=new cat_product();

$field_title_cat=new TextHTMLField();
$model['cat_product']->components['title']=new I18nField($field_title_cat);
$model['cat_product']->components['title']->required=1;

$model['cat_product']->components['subcat']=new ParentField('cat_product', 255);

$model['cat_product']->components['description']=new I18nField(new TextHTMLField());
$model['cat_product']->components['description']->required=1;

$model['cat_product']->components['view_only_mode']=new BooleanField();

$model['cat_product']->components['position']=new IntegerField();

$model['cat_product']->components['image_cat']=new ImageField('image_cat', $base_path.'application/media/shop/images/products/', $base_url.'/media/shop/images/products', 'image', 0);

class product_relationship extends Webmodel {

	function insert($post)
	{
	
		global $lang;
		
		if( !$this->select_count('where idproduct='.$post['idproduct'].' and idcat_product='.$post['idcat_product'], 'IdProduct_relationship') )
		{
		
			return parent::insert($post);
		
		}
		else
		{
			$this->std_error=$lang['shop']['product_is_already_on_category'];
		
			return false;
		
		}
	
	}

	function update($post, $conditions="")
	{
	
		global $lang;
		
		if( !$this->select_count('where idproduct='.$post['idproduct'].' and idcat_product='.$post['idcat_product'], 'IdProduct_relationship') )
		{
		
			return parent::update($post, $conditions);
		
		}
		else
		{
			$this->std_error=$lang['shop']['product_is_already_on_category'];
		
			return false;
		
		}
	
	}
	
}

$model['product_relationship']=new product_relationship('product_relationship');

$model['product_relationship']->components['idproduct']=new ForeignKeyField('product', 11);
$model['product_relationship']->components['idproduct']->required=1;
$model['product_relationship']->components['idcat_product']=new ForeignKeyField('cat_product', 11);
$model['product_relationship']->components['idcat_product']->required=1;
$model['product_relationship']->components['idcat_product']->name_field_to_field='title';


class taxes extends Webmodel {

	function __construct()
	{

		parent::__construct("taxes");

	}	
	
}

$model['taxes']=new taxes();

$model['taxes']->components['name']=new CharField(255);
$model['taxes']->components['name']->required=1;

$model['taxes']->components['percent']=new DoubleField(255);

$model['taxes']->components['country']=new ForeignKeyField('zone_shop');
$model['taxes']->components['country']->required=1;

class transport extends Webmodel {

	function __construct()
	{

		parent::__construct("transport");

	}	
	
}

$model['transport']=new transport();

$model['transport']->components['name']=new CharField(255);
$model['transport']->components['name']->required=1;

$model['transport']->components['country']=new ForeignKeyField('zone_shop');
$model['transport']->components['country']->required=1;

$model['transport']->components['type']=new ChoiceField($size=255, $type='integer', $arr_values=array(0, 1), $default_value=0);
$model['transport']->components['type']->required=0;

class price_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport");

	}	
	
}

$model['price_transport']=new price_transport();

$model['price_transport']->components['price']=new MoneyField();
$model['price_transport']->components['price']->required=1;

$model['price_transport']->components['weight']=new DoubleField();
$model['price_transport']->components['weight']->required=0;

$model['price_transport']->components['idtransport']=new ForeignKeyField('transport');
$model['price_transport']->components['idtransport']->form='HiddenForm';
$model['price_transport']->components['idtransport']->required=1;

class price_transport_price extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport_price");

	}	
	
}

$model['price_transport_price']=new price_transport_price();

$model['price_transport_price']->components['price']=new MoneyField();
$model['price_transport_price']->components['price']->required=0;

$model['price_transport_price']->components['min_price']=new MoneyField();
$model['price_transport_price']->components['min_price']->required=0;

$model['price_transport_price']->components['idtransport']=new ForeignKeyField('transport');
$model['price_transport_price']->components['idtransport']->form='HiddenForm';
$model['price_transport_price']->components['idtransport']->required=1;

class zone_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("zone_shop");

	}

	function update($post, $conditions="")
	{
	
		global $lang;

		settype($post['other_countries'], 'integer');
		settype($post['type'], 'integer');
		
		if($post['other_countries']==1 && isset($post['type']))
		{

			$num_count=parent::select_count('where other_countries=1 and type='.$post['type'], 'IdZone_shop');

			if($num_count>0)
			{

				$this->components['other_countries']->std_error=$lang['shop']['error_other_countries_is_selected'];

				return 0;

			}

		}
		
		return parent::update($post, $conditions);

	}
	
}

$model['zone_shop']=new zone_shop();

$model['zone_shop']->components['name']=new I18nField(new CharField(255));
$model['zone_shop']->components['name']->required=1;

/*foreach($arr_i18n as $lang_field)
{

	$model['zone_shop']->components['name_'.$lang_field]=new CharField(255);
	$model['zone_shop']->components['name_'.$lang_field]->required=1;

}*/

$model['zone_shop']->components['code']=new CharField(25);
$model['zone_shop']->components['code']->required=1;

//Code 0 is for transport
//Code 1 is for taxes

$model['zone_shop']->components['type']=new IntegerField(11);
$model['zone_shop']->components['type']->form='HiddenForm';

$model['zone_shop']->components['other_countries']=new BooleanField();

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

$model['country_shop']=new country_shop();

/*foreach($arr_i18n as $lang_field)
{

	$model['country_shop']->components['name_'.$lang_field]=new CharField(255);
	$model['country_shop']->components['name_'.$lang_field]->required=1;

}*/

$model['country_shop']->components['name']=new I18nField(new CharField(255));
$model['country_shop']->components['name']->required=1;

SlugifyField::add_slugify_i18n_fields('country_shop', 'name');

$model['country_shop']->components['code']=new CharField(25);
$model['country_shop']->components['code']->required=1;

$model['country_shop']->components['idzone_taxes']=new ForeignKeyField('zone_shop');
$model['country_shop']->components['idzone_transport']=new ForeignKeyField('zone_shop');

$model['country_user_shop']=new Webmodel('country_user_shop');

$model['country_user_shop']->components['iduser']=new IntegerField(11);
$model['country_user_shop']->components['idcountry']=new IntegerField(11);


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

$model['config_shop']=new config_shop();

$model['config_shop']->components['num_news']=new IntegerField(11);
$model['config_shop']->components['num_news']->required=1;
$model['config_shop']->components['yes_taxes']=new BooleanField();
$field_conditions=new TextHTMLField();
$model['config_shop']->components['conditions']=new I18nField($field_conditions);
//create_field_multilang('config_shop', 'conditions', $field_conditions, 0);
$model['config_shop']->components['yes_transport']=new BooleanField();
$model['config_shop']->components['type_index']=new CharField(25);
$model['config_shop']->components['ssl_url']=new BooleanField();

$field_title_shop=new TextHTMLField();
$model['config_shop']->components['title_shop']=new I18nField($field_title_shop);
//create_field_multilang('config_shop', 'title_shop', $field_title_shop, 0);*/
//$model['config_shop']->components['description_shop']->multilang=1;
$field_description_shop=new TextHTMLField();
$model['config_shop']->components['description_shop']=new I18nField($field_description_shop);
//create_field_multilang('config_shop', 'description_shop', $field_description_shop, 0);
//$model['config_shop']->components['cart_style']=new IntegerField(11);
$model['config_shop']->components['idtax']=new ForeignKeyField('taxes', 11);
$model['config_shop']->components['head_bill']=new CharField(255);
$model['config_shop']->components['num_begin_bill']=new IntegerField(11);
$model['config_shop']->components['elements_num_bill']=new IntegerField(11);
$model['config_shop']->components['image_bill']=new ImageField('image_bill', $base_path.'application/media/shop/images/products/', $base_url.'/media/shop/images/products', 'image', 0);

$model['config_shop']->components['bill_data_shop']=new TextField();
$model['config_shop']->components['bill_data_shop']->form='TextAreaForm';
$model['config_shop']->components['bill_data_shop']->br=0;
$model['config_shop']->components['footer_bill']=new TextField();
$model['config_shop']->components['footer_bill']->form='TextAreaForm';
$model['config_shop']->components['footer_bill']->br=0;

$model['config_shop']->components['explain_discounts_page']=new ForeignKeyField('page', 11);
$model['config_shop']->components['explain_discounts_page']->container_model='pages';

$model['config_shop']->components['idcurrency']=new ForeignKeyField('currency', 11);
$model['config_shop']->components['idcurrency']->required=1;

$model['config_shop']->components['view_only_mode']=new BooleanField();

class dir_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("dir_transport");

	}	
	
}

$model['dir_transport']=new dir_transport();

$model['dir_transport']->components['iduser']=new IntegerField();
$model['dir_transport']->components['name_transport']=new CharField(255);
$model['dir_transport']->components['last_name_transport']=new CharField(255);
$model['dir_transport']->components['enterprise_name_transport']=new CharField(255);
$model['dir_transport']->components['address_transport']=new CharField(255);
$model['dir_transport']->components['zip_code_transport']=new CharField(255);
$model['dir_transport']->components['phone_transport']=new CharField(255);
$model['dir_transport']->components['city_transport']=new CharField(255);
$model['dir_transport']->components['region_transport']=new CharField(255);
$model['dir_transport']->components['country_transport']=new IntegerField(11);
$model['dir_transport']->components['zone_transport']=new ForeignKeyField('zone_shop', 11);

class payment_form extends Webmodel {

	function __construct()
	{

		parent::__construct("payment_form");

	}	
	
}

$model['payment_form']=new payment_form();
$model['payment_form']->components['name']=new I18nField(new TextField());;
$model['payment_form']->components['name']->required=1;
$model['payment_form']->components['code']=new ChoiceField(255, 'string');
$model['payment_form']->components['price_payment']=new MoneyField();

class cart_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("cart_shop");

	}	
	
}

$model['cart_shop']=new cart_shop();
$model['cart_shop']->components['token']=new CharField(255);
$model['cart_shop']->components['idproduct']=new ForeignKeyField('product', 11);
$model['cart_shop']->components['idproduct']->fields_related_model=array('referer', 'title', 'extra_options');
$model['cart_shop']->components['price_product']=new MoneyField();
$model['cart_shop']->components['name_taxes_product']=new DoubleField();
$model['cart_shop']->components['taxes_product']=new DoubleField();
$model['cart_shop']->components['details']=new SerializeField();
$model['cart_shop']->components['time']=new IntegerField();

class order_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("order_shop");

	}	
	
}

$model['order_shop']=new order_shop();

$model['order_shop']->components['token']=new CharField(255);
$model['order_shop']->components['referer']=new CharField(255);
$model['order_shop']->components['name']=new CharField(255);
$model['order_shop']->components['last_name']=new CharField(255);
$model['order_shop']->components['enterprise_name']=new CharField(255);
$model['order_shop']->components['email']=new CharField(255);
$model['order_shop']->components['nif']=new CharField(255);
$model['order_shop']->components['address']=new CharField(255);
$model['order_shop']->components['zip_code']=new CharField(255);
$model['order_shop']->components['city']=new CharField(255);
$model['order_shop']->components['region']=new CharField(255);
$model['order_shop']->components['country']=new IntegerField(11);
$model['order_shop']->components['phone']=new CharField(255);
$model['order_shop']->components['fax']=new CharField(255);

$model['order_shop']->components['name_transport']=new CharField(255);
$model['order_shop']->components['last_name_transport']=new CharField(255);
$model['order_shop']->components['enterprise_name_transport']=new CharField(255);
$model['order_shop']->components['address_transport']=new CharField(255);
$model['order_shop']->components['zip_code_transport']=new CharField(255);
$model['order_shop']->components['city_transport']=new CharField(255);
$model['order_shop']->components['region_transport']=new CharField(255);
$model['order_shop']->components['country_transport']=new IntegerField(11);
$model['order_shop']->components['phone_transport']=new CharField(255);
$model['order_shop']->components['zone_transport']=new IntegerField(11);

$model['order_shop']->components['transport']=new CharField(255);

$model['order_shop']->components['payment_form']=new CharField(255);

$model['order_shop']->components['make_payment']=new BooleanField();

$model['order_shop']->components['observations']=new TextHTMLField();

$model['order_shop']->components['date_order']=new DateField();

$model['order_shop']->components['iduser']=new IntegerField(11);

$model['order_shop']->components['discount']=new CharField(255);
$model['order_shop']->components['discount_percent']=new PercentField();

$model['order_shop']->components['tax']=new CharField(255);
$model['order_shop']->components['tax_percent']=new PercentField();

$model['order_shop']->components['tax_discount_percent']=new PercentField();

$model['order_shop']->components['price_transport']=new MoneyField();
$model['order_shop']->components['transport_discount_percent']=new PercentField();

$model['order_shop']->components['name_payment']=new CharField(255);
$model['order_shop']->components['price_payment']=new MoneyField();
$model['order_shop']->components['payment_discount_percent']=new PercentField();

$model['order_shop']->components['total_price']=new MoneyField();

$model['order_shop']->components['name']->required=1;	
$model['order_shop']->components['last_name']->required=1;
$model['order_shop']->components['email']->required=1;
$model['order_shop']->components['address']->required=1;
$model['order_shop']->components['zip_code']->required=1;
$model['order_shop']->components['city']->required=1;
$model['order_shop']->components['region']->required=1;
$model['order_shop']->components['country']->required=1;
$model['order_shop']->components['phone']->required=1;

$model['order_shop']->components['name_transport']->required=1;	
$model['order_shop']->components['last_name_transport']->required=1;
$model['order_shop']->components['address_transport']->required=1;
$model['order_shop']->components['zip_code_transport']->required=1;
$model['order_shop']->components['city_transport']->required=1;
$model['order_shop']->components['region_transport']->required=1;
$model['order_shop']->components['country_transport']->required=1;
$model['order_shop']->components['zone_transport']->required=1;
$model['order_shop']->components['phone_transport']->required=1;

$model['order_shop']->components['token']->required=1;
$model['order_shop']->components['transport']->required=1;
$model['order_shop']->components['payment_form']->required=1;

$model['order_shop']->create_form();

$model['order_shop']->forms['referer']->label=$lang['shop']['referer'];
$model['order_shop']->forms['name']->label=$lang['user']['name'];
$model['order_shop']->forms['last_name']->label=$lang['user']['last_name'];
$model['order_shop']->forms['enterprise_name']->label=$lang['user']['enterprise_name'];
$model['order_shop']->forms['email']->label=$lang['user']['email'];
$model['order_shop']->forms['nif']->label=$lang['user']['nif'];
$model['order_shop']->forms['address']->label=$lang['common']['address'];
$model['order_shop']->forms['zip_code']->label=$lang['user']['zip_code'];
$model['order_shop']->forms['city']->label=$lang['user']['city'];
$model['order_shop']->forms['region']->label=$lang['common']['region'];
$model['order_shop']->forms['country']->label=$lang['common']['country'];
$model['order_shop']->forms['phone']->label=$lang['common']['phone'];
$model['order_shop']->forms['fax']->label=$lang['common']['fax'];
$model['order_shop']->forms['name_transport']->label=$lang['user']['name'];
$model['order_shop']->forms['last_name_transport']->label=$lang['user']['last_name'];
$model['order_shop']->forms['enterprise_name_transport']->label=$lang['user']['enterprise_name'];
$model['order_shop']->forms['address_transport']->label=$lang['common']['address'];
$model['order_shop']->forms['zip_code_transport']->label=$lang['common']['zip_code'];
$model['order_shop']->forms['city_transport']->label=$lang['common']['city'];
$model['order_shop']->forms['region_transport']->label=$lang['common']['region'];
$model['order_shop']->forms['country_transport']->label=$lang['common']['country'];
$model['order_shop']->forms['phone_transport']->label=$lang['common']['phone'];
$model['order_shop']->forms['zone_transport']->label=$lang['shop']['zone'];
$model['order_shop']->forms['transport']->label=$lang['shop']['transport'];
$model['order_shop']->forms['make_payment']->label=$lang['shop']['make_payment'];
$model['order_shop']->forms['observations']->label=$lang['shop']['observations'];
$model['order_shop']->forms['date_order']->label=$lang['common']['date'];

class type_product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("type_product_option");

	}	
	
}

$model['type_product_option']=new type_product_option();

$model['type_product_option']->components['title']=new I18nField(new TextField());
$model['type_product_option']->components['title']->required=1;

$model['type_product_option']->components['description']=new I18nField(new TextField());
$model['type_product_option']->components['description']->required=1;

$model['type_product_option']->components['question']=new I18nField(new TextField());
$model['type_product_option']->components['question']->required=1;

$model['type_product_option']->components['options']=new I18nField(new TextField());
$model['type_product_option']->components['options']->required=0;

$model['type_product_option']->components['price']=new MoneyField();
$model['type_product_option']->components['price']->required=0;


class product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("product_option");

	}	
	
}

$model['product_option']=new product_option();

$model['product_option']->components['idtype']=new ForeignKeyField('type_product_option', 11);

$model['product_option']->components['idtype']->required=1;

$model['product_option']->components['idtype']->fields_related_model=array('title');
$model['product_option']->components['idtype']->name_field_to_field='title';

$model['product_option']->components['idproduct']=new ForeignKeyField('product', 11);

$model['product_option']->components['idproduct']->form='HiddenForm';

$model['product_option']->components['idproduct']->required=1;

$model['product_option']->components['field_required']=new BooleanField();


class group_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop");

	}	
	
}

$model['group_shop']=new group_shop();

$model['group_shop']->components['name']=new I18nField(new CharField(255));
$model['group_shop']->components['name']->required=1;
$model['group_shop']->components['discount']=new PercentField(11);
$model['group_shop']->components['taxes_for_group']=new PercentField(11);
$model['group_shop']->components['transport_for_group']=new PercentField(11);
$model['group_shop']->components['shipping_costs_for_group']=new PercentField(11);

class group_shop_users extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop_users");

	}	
	
}

$model['group_shop_users']=new group_shop_users();

$model['group_shop_users']->components['iduser']=new ForeignKeyField('user', 11);
$model['group_shop_users']->components['iduser']->required=1;
$model['group_shop_users']->components['iduser']->fields_related_model=array('private_nick');
$model['group_shop_users']->components['iduser']->name_field_to_field='private_nick';
$model['group_shop_users']->components['group_shop']=new ForeignKeyField('group_shop', 11);
$model['group_shop_users']->components['group_shop']->form='HiddenForm';
$model['group_shop_users']->components['group_shop']->required=1;
$model['group_shop_users']->components['group_shop']->container_model='shop';

//Currency
class currency extends Webmodel {

	function __construct()
	{

		parent::__construct("currency");

	}

	function delete($conditions="")
	{

		//Cannot delete all and cannot delete currency selected...

		global $config_shop, $model;

		$arr_id=array(0);

		$query=$this->select($conditions, array('IdCurrency'));

		while(list($idcurrency)=webtsys_fetch_row($query))
		{

			$arr_id[]=$idcurrency;

			if($config_shop['idcurrency']==$idcurrency)
			{

				return 0;

			}

		}

		$query=$model['currency_change']->delete('where idcurrency IN ('.implode(', ', $arr_id).') or idcurrency_change IN ('.implode(', ', $arr_id).')');

		return parent::delete($conditions);

	}

}

$model['currency']=new currency('currency');

$model['currency']->components['name']=new I18nField(new TextField());

$model['currency']->components['symbol']=new CharField(25);

class currency_change extends Webmodel {

	function __construct()
	{

		parent::__construct("currency_change");

	}

	function insert($post)
	{

		global $lang;

		settype($post['idcurrency'], 'integer');
		settype($post['idcurrency_related'], 'integer');

		$num_change=$this->select_count('where idcurrency_related='.$post['idcurrency_related'].' and idcurrency='.$post['idcurrency'], 'IdCurrency_change');

		if($num_change==0)
		{

			return parent::insert($post);

		}
		else
		{

			$this->std_error=$lang['shop']['this_currency_have_equivalence'];
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

$model['currency_change']=new currency_change('currency_change');

$model['currency_change']->components['idcurrency']=new ParentField('currency', 11);

$model['currency_change']->components['idcurrency']->required=1;

$model['currency_change']->components['idcurrency_related']=new ForeignKeyField('currency', 11);
$model['currency_change']->components['idcurrency_related']->name_field_to_field='name';
$model['currency_change']->components['idcurrency_related']->fields_related_model=array('name');

$model['currency_change']->components['idcurrency_related']->required=1;

$model['currency_change']->components['change_value']=new MoneyField();

$model['currency_change']->components['change_value']->required=1;

//Class plugin_shop

$model['plugin_shop']=new Webmodel('plugin_shop');

$model['plugin_shop']->components['name']=new CharField(255);
$model['plugin_shop']->components['name']->required=1;

$model['plugin_shop']->components['element']=new ChoiceField($size=255, $type='string', $arr_values=array('product', 'cart', 'discounts'), $default_value='');
$model['plugin_shop']->components['element']->required=1;

$model['plugin_shop']->components['plugin']=new ChoiceField($size=255, $type='string', $arr_values=array(''), $default_value='');
$model['plugin_shop']->components['plugin']->required=1;

$model['plugin_shop']->components['position']=new IntegerField();

//$arr_plugin_list=array();

$arr_plugin_list['product'][]='attachments';

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


$model['product_attachments']=new product_attachments();

$model['product_attachments']->components['name']=new CharField(255);
$model['product_attachments']->components['name']->required=1;

$model['product_attachments']->components['file']=new FileField('file', $base_path.'/application/media/shop/files/', $base_url.'/media/shop/files', $type);
$model['product_attachments']->components['file']->required=1;

$model['product_attachments']->components['idproduct']=new ForeignKeyField('product', 11);
$model['product_attachments']->components['idproduct']->required=1;

class MoneyField extends DoubleField{


	function show_formatted($value)
	{

		return $this->currency_format($value);

	}

	
	static function currency_format($value, $symbol_view=1)
	{

		global $arr_currency, $arr_change_currency, $config_shop;
		
		$idcurrency=$_SESSION['idcurrency'];
	
		$symbol_currency=$arr_currency[$idcurrency];
		
		if($config_shop['idcurrency']!=$idcurrency)
		{

			//Make conversion

			$change_value=@$arr_change_currency[$config_shop['idcurrency']][$idcurrency];

			if($change_value>0)
			{

				$value=$value*$change_value;

			}
			else
			{
				//Obtain $change_value for inverse arr_change_currency

				if( isset($arr_change_currency[$idcurrency][$config_shop['idcurrency']]) )
				{

					/*$change_value=1/$arr_change_currency[$idcurrency][ $config_shop['idcurrency'] ];
					$value=$value*$change_value;*/
					$value=$value/$arr_change_currency[$idcurrency][ $config_shop['idcurrency'] ];

				}
				else
				{

					$symbol_currency=$arr_currency[$config_shop['idcurrency']];

				}

			}
			

		}
		
		$value=round($value, 2, PHP_ROUND_HALF_UP);
		
		$arr_symbol[0]='';
		$arr_symbol[1]=' '.$symbol_currency;
		
		return number_format($value, 2).$arr_symbol[$symbol_view];

	}

}

$arr_module_insert['shop']=array('name' => 'shop', 'admin' => 1, 'admin_script' => array('shop', 'shop'), 'load_module' => '', 'app_index' => 1);

$arr_module_sql['shop']='shop.sql';

$arr_module_remove['shop']=array('product', 'image_product', 'cat_product', 'taxes', 'transport', 'price_transport', 'zone_shop', 'country_shop', 'config_shop', 'dir_transport', 'payment_form', 'cart_shop', 'order_shop', 'type_product_option', 'product_option', 'group_shop', 'group_shop_users', 'currency', 'currency_change', );

?>

