<?php

//Utils::load_libraries(array('fields/i18nfield', 'fields/moneyfield', 'fields/passwordfield', 'models/userphangomodel'));

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\CoreFields\I18nField;
use PhangoApp\PhaModels\CoreFields\CharField;
use PhangoApp\PhaModels\CoreFields\IntegerField;
use PhangoApp\PhaModels\CoreFields\BooleanField;
use PhangoApp\PhaModels\CoreFields\SlugifyField;
use PhangoApp\PhaModels\CoreFields\ForeignKeyField;
use PhangoApp\PhaModels\CoreFields\PasswordField;
use PhangoApp\PhaModels\CoreFields\ChoiceField;
use PhangoApp\PhaModels\CoreFields\TextField;
use PhangoApp\PhaModels\CoreFields\ParentField;
use PhangoApp\PhaModels\CoreFields\MoneyField;
use PhangoApp\PhaModels\CoreFields\TextHTMLField;
use PhangoApp\PhaModels\CoreFields\DoubleField;
use PhangoApp\PhaModels\CoreFields\DateField;
use PhangoApp\PhaModels\CoreFields\ImageField;
use PhangoApp\PhaModels\CoreFields\ArrayField;
use PhangoApp\PhaModels\CoreFields\PercentField;
use PhangoApp\PhaModels\CoreFields\FileField;
use PhangoApp\PhaModels\ExtraModels\UserPhangoModel;

I18n::load_lang('shop');
I18n::load_lang('common');
I18n::load_lang('users');

//Moneyfield

class ShopMoneyField extends MoneyField{

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

//Countries. A Country is from a zone_shop

class country_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("country_shop");

	}	
	
	function insert($post, $safe_query = 0, $cache_name = '')
	{
	
		$post=$this->components['name']->add_slugify_i18n_post('name', $post);
	
		return parent::insert($post);
	
	}
	
	function update($post, $safe_query = 0, $cache_name = '')
	{
	
		$post=$this->components['name']->add_slugify_i18n_post('name', $post);
	
		return parent::update($post);
	
	}
	
}

class zone_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("zone_shop");

	}

	function update($post, $safe_query = 0, $cache_name = '')
	{

		settype($post['other_countries'], 'integer');
		settype($post['type'], 'integer');
		
		if($post['other_countries']==1 && isset($post['type']))
		{

            $old_conditions=$this->conditions;
		
            $this->conditions='where other_countries=1 and type='.$post['type'];
		
			$num_count=parent::select_count('IdZone_shop');

			$this->conditions=$old_conditions;
			
			if($num_count>0)
			{

				$this->components['other_countries']->std_error=I18n::lang('shop', 'error_other_countries_is_selected', 'Error: se ha seleccionado que esta zona abarque el resto de países, pero no se ha especificado el tipo de zona');

				return 0;

			}

		}
		
		return parent::update($post, $safe_query, $cache_name);

	}
	
}

Webmodel::$model['zone_shop']=new zone_shop();

Webmodel::$model['zone_shop']->register('name', new I18nField(new CharField(255)), 1);

/*foreach($arr_i18n as PhangoVar::$lang_field)
{

	Webmodel::$model['zone_shop']->components['name_'.PhangoVar::$lang_field]=new CharField(255);
	Webmodel::$model['zone_shop']->components['name_'.PhangoVar::$lang_field]->required=1;

}*/

Webmodel::$model['zone_shop']->register('code', new CharField(25), 1);

//Code 0 is for transport
//Code 1 is for taxes

Webmodel::$model['zone_shop']->register('type', new IntegerField(11));
Webmodel::$model['zone_shop']->components['type']->form='PhangoApp\PhaModels\Forms\HiddenForm';

Webmodel::$model['zone_shop']->register('other_countries', new BooleanField());

Webmodel::$model['country_shop']=new country_shop();

/*foreach($arr_i18n as PhangoVar::$lang_field)
{

	Webmodel::$model['country_shop']->components['name_'.PhangoVar::$lang_field]=new CharField(255);
	Webmodel::$model['country_shop']->components['name_'.PhangoVar::$lang_field]->required=1;

}*/

Webmodel::$model['country_shop']->register('name', new I18nField(new CharField(255)) , 1);

//SlugifyField::add_slugify_i18n_fields('country_shop', 'name');
Webmodel::$model['country_shop']=SlugifyField::add_slugify_i18n_fields(Webmodel::$model['country_shop'], 'name');

Webmodel::$model['country_shop']->register('code', new CharField(25), 1);

//Webmodel::$model['country_shop']->register('idzone_taxes', new ForeignKeyField('zone_shop'));
Webmodel::$model['country_shop']->register('idzone_transport', new ForeignKeyField(Webmodel::$model['zone_shop'], 11, $default_id=0, $name_field='name', $name_value='IdZone_shop'));

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

Webmodel::$model['user_shop']->register('email', new CharField(255), 1);

Webmodel::$model['user_shop']->register('password', new PasswordField(255), 1);

Webmodel::$model['user_shop']->register('token_client', new CharField(255), 1);

Webmodel::$model['user_shop']->register('token_recovery', new CharField(255), 1);

Webmodel::$model['user_shop']->register('name', new CharField(255), 1);

Webmodel::$model['user_shop']->register('last_name', new CharField(255), 1);
Webmodel::$model['user_shop']->register('address', new CharField(255), 1);
Webmodel::$model['user_shop']->register('zip_code', new CharField(255), 1);
Webmodel::$model['user_shop']->register('region', new CharField(255), 1);
Webmodel::$model['user_shop']->register('city', new CharField(255), 1);
Webmodel::$model['user_shop']->register('country', new ForeignKeyField(Webmodel::$model['country_shop'], 11, $default_id=0, $name_field='name', $name_value='IdCountry_shop'), 1);
Webmodel::$model['user_shop']->register('phone', new CharField(255), 1);//Only for special effects...
Webmodel::$model['user_shop']->register('fax', new CharField(255));//Only for special effects...
Webmodel::$model['user_shop']->register('nif', new CharField(255), 1);//Only for special effects...
Webmodel::$model['user_shop']->register('enterprise_name', new CharField(255));//Only for special effects...
Webmodel::$model['user_shop']->register('last_connection', new IntegerField(11));
Webmodel::$model['user_shop']->register('format_date', new ChoiceField(10, 'string', array('d-m-Y', 'Y-m-d')));
Webmodel::$model['user_shop']->register('format_time', new IntegerField(11));
Webmodel::$model['user_shop']->register('timezone', new ChoiceField(35, 'string', array(), MY_TIMEZONE));
Webmodel::$model['user_shop']->register('ampm', new ChoiceField(10, 'string', array('H:i:s', 'h:i:s A'), MY_TIMEZONE));

Webmodel::$model['user_shop']->register('disabled', new BooleanField());

Webmodel::$model['country_user_shop']=new Webmodel('country_user_shop');

Webmodel::$model['country_user_shop']->register('iduser', new IntegerField(11));
Webmodel::$model['country_user_shop']->register('idcountry', new IntegerField(11));

Webmodel::$model['currency']=new currency('currency');

Webmodel::$model['currency']->register('name', new I18nField(new TextField()) , 1);

Webmodel::$model['currency']->register('symbol', new CharField(25), 1);

class currency_change extends Webmodel {

	function __construct()
	{

		parent::__construct("currency_change");

	}

	function insert($post, $safe_query = 0, $cache_name = '')
	{

		settype($post['idcurrency'], 'integer');
		settype($post['idcurrency_related'], 'integer');

		$old_conditions=$this->conditions;
		
		$this->conditions='where idcurrency_related='.$post['idcurrency_related'].' and idcurrency='.$post['idcurrency'];
		
		$num_change=$this->select_count();

		$this->conditions=$old_conditions;
		
		if($num_change==0)
		{

			return parent::insert($post, $safe_query, $cache_name);

		}
		else
		{

			$this->std_error=I18n::lang('shop', 'this_currency_have_equivalence', 'Ya ha aplicado un valor a esta moneda. Edite la relación correspondiente creada anteriormente, o cambie la moneda a la que quiere aplicar la equivalencia');
			return 0;

		}

	}

	function update($post, $safe_query = 0, $cache_name = '')
	{

		//We need see if exists the row with this data

		settype($post['idcurrency_related'], 'integer');

		$query=$this->select('where idcurrency_related='.$post['idcurrency_related'].' and idcurrency='.$post['idcurrency'], array('IdCurrency_change'));

		list($idcurrency_change)=$this->fetch_row($query);

		settype($idcurrency_change, 'integer');

		//Well, use $idcurrency_change how conditions...

		$this->conditions='where IdCurrency_change='.$idcurrency_change;

		return parent::update($post, $safe_query, $cache_name);

	}
	
}

Webmodel::$model['currency_change']=new currency_change('currency_change');

Webmodel::$model['currency_change']->register('idcurrency', new ForeignKeyField(Webmodel::$model['currency'], 11, $default_id=0, $name_field='name', $name_value='IdCurrency'), 1);

Webmodel::$model['currency_change']->register('idcurrency_related', new ForeignKeyField(Webmodel::$model['currency'], 11, $default_id=0, $name_field='name', $name_value='IdCurrency'), 1);
Webmodel::$model['currency_change']->components['idcurrency_related']->name_field_to_field='name';
//Webmodel::$model['currency_change']->components['idcurrency_related']->fields_related_model=array('name');

Webmodel::$model['currency_change']->register('change_value', new ShopMoneyField(), 1);

//Product class

class product extends Webmodel {

	public function __construct()
	{

		parent::__construct("product");

	}
	
	public function insert($post, $safe_query = 0, $cache_name = '')
	{
	
		$post=$this->components['title']->add_slugify_i18n_post('title', $post);
	
		if(parent::insert($post, $safe_query, $cache_name))
		{
		
			settype($_GET['idcat'], 'integer');
		
			$idproduct=parent::insert_id();
			
			Webmodel::$model['product_relationship']->fields_to_update=['idproduct', 'idcat_product'];
			
			
		
			if( !Webmodel::$model['product_relationship']->insert(array('idproduct' => $idproduct, 'idcat_product' => $_GET['idcat'])) )
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
	
	public function update($post, $safe_query = 0, $cache_name = '')
	{
	
		$post=$this->components['title']->add_slugify_i18n_post('title', $post);
	
		return parent::update($post, $safe_query, $cache_name);
	
	}
	
	public function delete()
	{
	
		//Obtain ids for this product for delete images of product.
		
		$old_conditions=$this->conditions;
		
		$query=$this->select(array('IdProduct'));
		
		$arr_id_prod=array(0);
		
		while(list($idproduct)=$this->fetch_row($query))
		{
		
			$arr_id_prod[]=$idproduct;
		
		}
		
		$this->conditions=$old_conditions;
		
		Webmodel::$model['image_product']->conditions='where image_product.idproduct IN ('.implode(', ', $arr_id_prod).')';
		
		Webmodel::$model['image_product']->delete();
		
		Webmodel::$model['product_relationship']->conditions='where product_relationship.idproduct IN ('.implode(', ', $arr_id_prod).')';
		
		Webmodel::$model['product_relationship']->delete();
		
		if(!parent::delete())
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

Webmodel::$model['product']->register('referer', new CharField(255), 1);

Webmodel::$model['product']->register('title', new I18nField(new CharField(255)), 1);

Webmodel::$model['product']=SlugifyField::add_slugify_i18n_fields(Webmodel::$model['product'], 'title');

Webmodel::$model['product']->register('description', new I18nField(new TextHTMLField()), 1);

Webmodel::$model['product']->register('description_short', new I18nField(new CharField(1000)));

//Webmodel::$model['product']->register('idcat', new ForeignKeyField('cat_product', 11));

//Webmodel::$model['product']->components['idcat']->required=1;

Webmodel::$model['product']->register('price', new ShopMoneyField());

Webmodel::$model['product']->register('special_offer', new DoubleField());

Webmodel::$model['product']->register('stock', new BooleanField());

Webmodel::$model['product']->register('date', new DateField());

Webmodel::$model['product']->register('about_order', new BooleanField());

//Webmodel::$model['product']->register('extra_options', new ChoiceField(255, 'string'));

Webmodel::$model['product']->register('weight', new DoubleField());

Webmodel::$model['product']->register('num_sold', new IntegerField());

Webmodel::$model['product']->register('cool', new BooleanField());

class image_product extends Webmodel {

	function __construct()
	{

		parent::__construct("image_product");

	}

	function insert($post, $safe_query = 0, $cache_name = '')
	{
		
		settype($post['principal'], 'integer');
		settype($post['idproduct'], 'integer');

		//If is not defined idproduct cannot change principal image.

		if($post['idproduct']>0)
		{

            $old_conditions=$this->conditions;
		
            $this->conditions='where principal=1 and idproduct='.$post['idproduct'];
		
			$num_principal_photo=$this->select_count();

			$this->conditions=$old_conditions;
			
			if($num_principal_photo==0)
			{

				$post['principal']=1;

			}

			if($post['principal']==1)
			{

				$query=$this->query('update image_product set principal=0 where idproduct='.$post['idproduct']);

			}

		}
		else
		{

			//Unset principal

			unset($post['principal']);

		}

		return Webmodel::insert($post);

	}
	
	function update($post, $safe_query = 0, $cache_name = '')
	{
		
		settype($post['principal'], 'integer');
		settype($post['idproduct'], 'integer');

		//If is not defined idproduct cannot change principal image.
		
		//$query=$this->select('where idproduct='.);
		
		//Array ( [photo] => arcoiris_jaen.jpg [idproduct] => 10 [principal] => 1 ) 
		
			//if exists a new photo...
		
		if($post['idproduct']>0)
		{

			$old_conditions=$this->conditions;
        
            $this->conditions='where principal=1 and idproduct='.$post['idproduct'];
        
            $num_principal_photo=$this->select_count();

            $this->conditions=$old_conditions;

			if($num_principal_photo==0)
			{

				$post['principal']=1;

			}

			if($post['principal']==1)
			{

				$query=$this->query('update image_product set principal=0 where idproduct='.$post['idproduct']);

			}

		}
		else
		{

			//Unset principal

			unset($post['principal']);

		}
		
		$return_file=parent::update($post, $safe_query, $cache_name);
		
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

	function delete()
	{

		//Delete images from field...
		
		$query=$this->select(array('IdImage_product', 'principal', 'photo', 'idproduct'));

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
				
				$query2=$this->query('update image_product set principal=1 where idproduct='.$idproduct.' and IdImage_product!='.$idimage.' limit 1');

			}

		}

 		return $this->query('delete from '.$this->name.' '.$conditions);
		
	}

}

Webmodel::$model['image_product']=new image_product();

Webmodel::$model['image_product']->register('principal', new BooleanField());

//($path, $url_path, $thumb=0, $img_width=array('mini' => 150), $quality_jpeg=85)

Webmodel::$model['image_product']->register('photo', new ImageField('shop/products/images', Routes::$root_url.'/shop/products/images', 1, array('small' => 45, 'mini' => 150, 'medium' => 300, 'preview' => 600)), 1);
Webmodel::$model['image_product']->register('idproduct', new ForeignKeyField(Webmodel::$model['product'], 11, $default_id=0, $name_field='title', $name_value='IdProduct'), 1);


class cat_product extends Webmodel {

	function __construct()
	{

		parent::__construct("cat_product");

	}	
	
	function delete()
	{
		
		$arr_id_cat_product=array(0);
		
		$query=$this->select(array('IdCat_product'));
		
		while(list($idcat_product)=$this->fetch_row($query))
		{
		
			//$query=Webmodel::$model['product']->delete('where idcat='.$idcat_product);
			$arr_idcat_product[]=$idcat_product;
		
		}
		
		//Delete relationships...
		
		Webmodel::$model['product_relationship']->conditions='where idcat_product IN ('.implode(',', $arr_idcat_product).')';
		
		Webmodel::$model['product_relationship']->delete();
	
		return parent::delete();
	
	}

}

Webmodel::$model['cat_product']=new cat_product();

$field_title_cat=new TextHTMLField();

Webmodel::$model['cat_product']->register('title', new I18nField($field_title_cat), 1);

Webmodel::$model['cat_product']->register('subcat', new ParentField(11, Webmodel::$model['cat_product'], $name_field='title', $name_value='IdCat_product'));

Webmodel::$model['cat_product']->register('description', new I18nField(new TextHTMLField()) , 1);

Webmodel::$model['cat_product']->register('view_only_mode', new BooleanField());

Webmodel::$model['cat_product']->register('position', new IntegerField());

Webmodel::$model['cat_product']->register('image_cat', new ImageField('image_cat', 'shop/categories/images/', Routes::$root_url.'/shop/categories/images/', 'image', 0) );

class product_relationship extends Webmodel {

	function insert($post, $safe_query = 0, $cache_name = '')
	{
		
		$old_conditions=$this->conditions;
		
		$this->conditions='where idproduct='.$post['idproduct'].' and idcat_product='.$post['idcat_product'];
		
		if( !$this->select_count('IdProduct_relationship') )
		{
		
            $this->conditions=$old_conditions;
		
			return parent::insert($post, $safe_query, $cache_name);
		
		}
		else
		{
            $this->conditions=$old_conditions;
		
			$this->std_error=I18n::lang('shop', 'product_is_already_on_category', 'Este producto está realmente en la categoría');
		
			return false;
		
		}
	
	}

	function update($post, $safe_query = 0, $cache_name = '')
	{
		
		if( !$this->select_count('where idproduct='.$post['idproduct'].' and idcat_product='.$post['idcat_product'], 'IdProduct_relationship') )
		{
		
			return parent::update($post, $safe_query, $cache_name);
		
		}
		else
		{
			$this->std_error=I18n::lang('shop', 'product_is_already_on_category', 'Este producto está realmente en la categoría');
		
			return false;
		
		}
	
	}
	
}

Webmodel::$model['product_relationship']=new product_relationship('product_relationship');

//, $default_id=0, $name_field='', $name_value='')

Webmodel::$model['product_relationship']->register('idproduct', new ForeignKeyField(Webmodel::$model['product'], 11, $default_id=0, $name_field='title', $name_value='IdProduct'), 1);

Webmodel::$model['product_relationship']->register('idcat_product', new ForeignKeyField(Webmodel::$model['cat_product'], 11, $default_id=0, $name_field='title', $name_value='IdCat_product'), 1);
Webmodel::$model['product_relationship']->components['idcat_product']->name_field_to_field='title';


/*class taxes extends Webmodel {

	function __construct()
	{

		parent::__construct("taxes");

	}	
	
}

Webmodel::$model['taxes']=new taxes();

Webmodel::$model['taxes']->register('name', new CharField(255));
Webmodel::$model['taxes']->components['name']->required=1;

Webmodel::$model['taxes']->register('percent', new DoubleField(255));

Webmodel::$model['taxes']->register('country', new ForeignKeyField('zone_shop'));
Webmodel::$model['taxes']->components['country']->required=1;*/

class transport extends Webmodel {

	function __construct()
	{

		parent::__construct("transport");

	}	
	
}

Webmodel::$model['transport']=new transport();

Webmodel::$model['transport']->register('name', new CharField(255), 1);

Webmodel::$model['transport']->register('country', new ForeignKeyField(Webmodel::$model['zone_shop'], 11, $default_id=0, $name_field='name', $name_value='IdZone_shop'), 1);

Webmodel::$model['transport']->register('type', new ChoiceField($size=11, $type='integer', $arr_values=array(0, 1), $default_value=0));

class price_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport");

	}	
	
}

Webmodel::$model['price_transport']=new price_transport();

Webmodel::$model['price_transport']->register('price', new ShopMoneyField());
Webmodel::$model['price_transport']->components['price']->required=1;

Webmodel::$model['price_transport']->register('weight', new DoubleField());
Webmodel::$model['price_transport']->components['weight']->required=0;

Webmodel::$model['price_transport']->register('idtransport', new ForeignKeyField(Webmodel::$model['transport'], 11, $default_id=0, $name_field='name', $name_value=''));
Webmodel::$model['price_transport']->components['idtransport']->form='HiddenForm';
Webmodel::$model['price_transport']->components['idtransport']->required=1;

class price_transport_price extends Webmodel {

	function __construct()
	{

		parent::__construct("price_transport_price");

	}	
	
}

Webmodel::$model['price_transport_price']=new price_transport_price();

Webmodel::$model['price_transport_price']->register('price', new ShopMoneyField());
Webmodel::$model['price_transport_price']->components['price']->required=0;

Webmodel::$model['price_transport_price']->register('min_price', new ShopMoneyField());
Webmodel::$model['price_transport_price']->components['min_price']->required=0;

Webmodel::$model['price_transport_price']->register('idtransport', new ForeignKeyField(Webmodel::$model['transport'], 11, $default_id=0, $name_field='name', $name_value='IdTransport'));
Webmodel::$model['price_transport_price']->components['idtransport']->form='HiddenForm';
Webmodel::$model['price_transport_price']->components['idtransport']->required=1;


class config_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("config_shop");

	}

	function update($post, $safe_query = 0, $cache_name = '')
	{

		settype($_POST['num_begin_bill'], 'integer');

		if($_POST['num_begin_bill']<0)
		{

			$_POST['num_begin_bill']=1;

		}

		$query=$this->query('ALTER TABLE order_shop AUTO_INCREMENT = '.$_POST['num_begin_bill']);
		

		return parent::update($post, $safe_query, $cache_name);

	}
	
}

Webmodel::$model['config_shop']=new config_shop();

Webmodel::$model['config_shop']->register('num_news', new IntegerField(11));

/*Webmodel::$model['config_shop']->register('num_news', new IntegerField(11));
Webmodel::$model['config_shop']->components['num_news']->required=1;*/
/*Webmodel::$model['config_shop']->register('yes_taxes', new BooleanField());*/
$field_conditions=new TextHTMLField();
Webmodel::$model['config_shop']->register('conditions', new I18nField($field_conditions));
//create_field_multilang('config_shop', 'conditions', $field_conditions, 0);
Webmodel::$model['config_shop']->register('no_transport', new BooleanField());
//Webmodel::$model['config_shop']->register('type_index', new CharField(25));
//Webmodel::$model['config_shop']->register('ssl_url', new BooleanField());

$field_title_shop=new TextHTMLField();
Webmodel::$model['config_shop']->register('title_shop', new I18nField($field_title_shop));
//create_field_multilang('config_shop', 'title_shop', $field_title_shop, 0);*/
//Webmodel::$model['config_shop']->components['description_shop']->multilang=1;
$field_description_shop=new TextHTMLField();
Webmodel::$model['config_shop']->register('description_shop', new I18nField($field_description_shop));
//create_field_multilang('config_shop', 'description_shop', $field_description_shop, 0);
//Webmodel::$model['config_shop']->register('cart_style', new IntegerField(11));
//Webmodel::$model['config_shop']->register('idtax', new ForeignKeyField('taxes', 11));
Webmodel::$model['config_shop']->register('head_bill', new CharField(255));
Webmodel::$model['config_shop']->register('num_begin_bill', new IntegerField(11));
Webmodel::$model['config_shop']->register('elements_num_bill', new IntegerField(11));
Webmodel::$model['config_shop']->register('image_bill', new ImageField('image_bill', 'shop/bill/images/', Routes::$root_url.'/shop/bill/images', 'image', 0));

Webmodel::$model['config_shop']->register('bill_data_shop', new TextField());
Webmodel::$model['config_shop']->components['bill_data_shop']->br=0;
Webmodel::$model['config_shop']->register('footer_bill', new TextField());
Webmodel::$model['config_shop']->components['footer_bill']->br=0;

/*Webmodel::$model['config_shop']->register('explain_discounts_page', new ForeignKeyField('page', 11));
Webmodel::$model['config_shop']->components['explain_discounts_page']->container_model='pages';*/

Webmodel::$model['config_shop']->register('idcurrency', new ForeignKeyField(Webmodel::$model['currency'], 11, $default_id=0, $name_field='name', $name_value='IdCurrency'));
Webmodel::$model['config_shop']->components['idcurrency']->required=1;

Webmodel::$model['config_shop']->register('view_only_mode', new BooleanField());

class address_transport extends Webmodel {

	function __construct()
	{

		parent::__construct("address_transport");

	}	
	
}

Webmodel::$model['address_transport']=new address_transport();

Webmodel::$model['address_transport']->register('iduser', new ForeignKeyField(Webmodel::$model['user_shop'], 11, $default_id=0, $name_field='email', $name_value='IdUser_shop'), 1);
Webmodel::$model['address_transport']->register('name_transport', new CharField(255), 1);
Webmodel::$model['address_transport']->register('last_name_transport', new CharField(255), 1);
Webmodel::$model['address_transport']->register('enterprise_name_transport', new CharField(255));
Webmodel::$model['address_transport']->register('address_transport', new CharField(255), 1);
Webmodel::$model['address_transport']->register('zip_code_transport', new CharField(255), 1);
Webmodel::$model['address_transport']->register('phone_transport', new CharField(255), 1);
Webmodel::$model['address_transport']->register('city_transport', new CharField(255), 1);
Webmodel::$model['address_transport']->register('region_transport', new CharField(255), 1);
Webmodel::$model['address_transport']->register('country_transport', new ForeignKeyField(Webmodel::$model['country_shop'], 11, 0, 'name', 'IdCountry_shop'), 1);
//Webmodel::$model['address_transport']->register('zone_transport', new ForeignKeyField('zone_shop', 11));

class payment_form extends Webmodel {

	function __construct()
	{

		parent::__construct("payment_form");

	}	
	
}

Webmodel::$model['payment_form']=new payment_form();
Webmodel::$model['payment_form']->register('name', new I18nField(new TextField()) , 1);
Webmodel::$model['payment_form']->register('code', new ChoiceField(255, 'string'), 1);
Webmodel::$model['payment_form']->register('price_payment', new ShopMoneyField());

class cart_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("cart_shop");

	}	
	
}

Webmodel::$model['cart_shop']=new cart_shop();
Webmodel::$model['cart_shop']->register('token', new CharField(255));
Webmodel::$model['cart_shop']->register('idproduct', new ForeignKeyField(Webmodel::$model['product'], 11));
Webmodel::$model['cart_shop']->components['idproduct']->fields_related_model=array('referer', 'title');
Webmodel::$model['cart_shop']->register('price_product', new ShopMoneyField());
/*Webmodel::$model['cart_shop']->register('name_taxes_product', new DoubleField());
Webmodel::$model['cart_shop']->register('taxes_product', new DoubleField());*/
Webmodel::$model['cart_shop']->register('units', new IntegerField());
Webmodel::$model['cart_shop']->register('details', new ArrayField(new CharField(255)));
Webmodel::$model['cart_shop']->register('alter_price_elements', new ArrayField(new ShopMoneyField()));
Webmodel::$model['cart_shop']->register('time', new IntegerField());
Webmodel::$model['cart_shop']->register('weight', new DoubleField());

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

Webmodel::$model['order_shop']->register('token', new CharField(255));
Webmodel::$model['order_shop']->register('referer', new CharField(255));
Webmodel::$model['order_shop']->register('name', new CharField(255));
Webmodel::$model['order_shop']->register('last_name', new CharField(255));
Webmodel::$model['order_shop']->register('enterprise_name', new CharField(255));
Webmodel::$model['order_shop']->register('email', new CharField(255));
Webmodel::$model['order_shop']->register('nif', new CharField(255));
Webmodel::$model['order_shop']->register('address', new CharField(255));
Webmodel::$model['order_shop']->register('zip_code', new CharField(255));
Webmodel::$model['order_shop']->register('city', new CharField(255));
Webmodel::$model['order_shop']->register('region', new CharField(255));
Webmodel::$model['order_shop']->register('country', new I18nField(new TextField()));
Webmodel::$model['order_shop']->register('phone', new CharField(255));
Webmodel::$model['order_shop']->register('fax', new CharField(255));

Webmodel::$model['order_shop']->register('name_transport', new CharField(255));
Webmodel::$model['order_shop']->register('last_name_transport', new CharField(255));
Webmodel::$model['order_shop']->register('enterprise_name_transport', new CharField(255));
Webmodel::$model['order_shop']->register('address_transport', new CharField(255));
Webmodel::$model['order_shop']->register('zip_code_transport', new CharField(255));
Webmodel::$model['order_shop']->register('city_transport', new CharField(255));
Webmodel::$model['order_shop']->register('region_transport', new CharField(255));
Webmodel::$model['order_shop']->register('country_transport', new I18nField(new TextField()));
Webmodel::$model['order_shop']->register('phone_transport', new CharField(255));
Webmodel::$model['order_shop']->register('address_transport_id', new IntegerField(11));

Webmodel::$model['order_shop']->register('transport', new CharField(255));
Webmodel::$model['order_shop']->register('price_transport', new ShopMoneyField());

Webmodel::$model['order_shop']->register('name_payment', new CharField(255));
Webmodel::$model['order_shop']->register('price_payment', new ShopMoneyField());

Webmodel::$model['order_shop']->register('finished', new BooleanField());

Webmodel::$model['order_shop']->register('payment_done', new BooleanField());

Webmodel::$model['order_shop']->register('observations', new TextHTMLField());

Webmodel::$model['order_shop']->register('date_order', new DateField());

Webmodel::$model['order_shop']->register('iduser', new ForeignKeyField(Webmodel::$model['user_shop']), 1);

//Webmodel::$model['order_shop']->register('iduser', new ForeignKeyField('user_shop'));

//Webmodel::$model['order_shop']->register('payment_discount_percent', new PercentField());

Webmodel::$model['order_shop']->register('total_price', new ShopMoneyField());

/*Webmodel::$model['order_shop']->register('invoice_num', new ForeignKeyField('invoice_num'));
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

Webmodel::$model['order_shop']->create_forms();

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

Webmodel::$model['order_shop_plugins']->register('idorder_shop', new ForeignKeyField(Webmodel::$model['order_shop']));
Webmodel::$model['order_shop_plugins']->register('name', new I18nField('order_shop'));
Webmodel::$model['order_shop_plugins']->register('add_price', new ShopMoneyField());
Webmodel::$model['order_shop_plugins']->register('idcart_shop', new ForeignKeyField(Webmodel::$model['cart_shop']));

Webmodel::$model['invoice_num']=new Webmodel('invoice_num');

Webmodel::$model['invoice_num']->change_id_default('invoice_num');

Webmodel::$model['invoice_num']->register('token_shop', new CharField(255));

class type_product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("type_product_option");

	}	
	
}

Webmodel::$model['type_product_option']=new type_product_option();

Webmodel::$model['type_product_option']->register('title', new I18nField(new TextField()));
Webmodel::$model['type_product_option']->components['title']->required=1;

Webmodel::$model['type_product_option']->register('description', new I18nField(new TextField()));
Webmodel::$model['type_product_option']->components['description']->required=1;

Webmodel::$model['type_product_option']->register('question', new I18nField(new TextField()));
Webmodel::$model['type_product_option']->components['question']->required=1;

Webmodel::$model['type_product_option']->register('options', new I18nField(new TextField()));
Webmodel::$model['type_product_option']->components['options']->required=0;

Webmodel::$model['type_product_option']->register('price', new ShopMoneyField());
Webmodel::$model['type_product_option']->components['price']->required=0;


class product_option extends Webmodel {

	function __construct()
	{

		parent::__construct("product_option");

	}	
	
}

Webmodel::$model['product_option']=new product_option();

Webmodel::$model['product_option']->register('idtype', new ForeignKeyField(Webmodel::$model['type_product_option'], 11));

Webmodel::$model['product_option']->components['idtype']->required=1;

Webmodel::$model['product_option']->components['idtype']->fields_related_model=array('title');
Webmodel::$model['product_option']->components['idtype']->name_field_to_field='title';

Webmodel::$model['product_option']->register('idproduct', new ForeignKeyField(Webmodel::$model['product'], 11));

Webmodel::$model['product_option']->components['idproduct']->form='HiddenForm';

Webmodel::$model['product_option']->components['idproduct']->required=1;

Webmodel::$model['product_option']->register('field_required', new BooleanField());


class group_shop extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop");

	}	
	
}

Webmodel::$model['group_shop']=new group_shop();

Webmodel::$model['group_shop']->register('name', new I18nField(new CharField(255)));
Webmodel::$model['group_shop']->components['name']->required=1;
Webmodel::$model['group_shop']->register('discount', new PercentField(11));
//Webmodel::$model['group_shop']->register('taxes_for_group', new PercentField(11));
Webmodel::$model['group_shop']->register('transport_for_group', new PercentField(11));
Webmodel::$model['group_shop']->register('shipping_costs_for_group', new PercentField(11));

class group_shop_users extends Webmodel {

	function __construct()
	{

		parent::__construct("group_shop_users");

	}	
	
}

Webmodel::$model['group_shop_users']=new group_shop_users();

Webmodel::$model['group_shop_users']->register('iduser', new ForeignKeyField(Webmodel::$model['user_shop'], 11));
Webmodel::$model['group_shop_users']->components['iduser']->required=1;
Webmodel::$model['group_shop_users']->components['iduser']->fields_related_model=array('private_nick');
Webmodel::$model['group_shop_users']->components['iduser']->name_field_to_field='private_nick';
Webmodel::$model['group_shop_users']->register('group_shop', new ForeignKeyField(Webmodel::$model['group_shop'], 11));
Webmodel::$model['group_shop_users']->components['group_shop']->form='HiddenForm';
Webmodel::$model['group_shop_users']->components['group_shop']->required=1;
Webmodel::$model['group_shop_users']->components['group_shop']->container_model='shop';

//Currency
class currency extends Webmodel {

	function __construct()
	{

		parent::__construct("currency");

	}

	function delete()
	{

		//Cannot delete all and cannot delete currency selected...

		$arr_id=array(0);

		$reset_cond_options=$this->reset_conditions;
		
		$this->reset_conditions=0;
		
		$query=$this->select(array('IdCurrency'));

		while(list($idcurrency)=$this->fetch_row($query))
		{

			$arr_id[]=$idcurrency;

			if(ConfigShop::$config_shop['idcurrency']==$idcurrency)
			{

				return 0;

			}

		}

		Webmodel::$model['currency_change']->conditions='where idcurrency IN ('.implode(', ', $arr_id).') or idcurrency_change IN ('.implode(', ', $arr_id).')';
		
		Webmodel::$model['currency_change']->delete();

		$this->reset_conditions=$reset_cond_options;
		
		return parent::delete();

	}

}

//Class plugin_shop

Webmodel::$model['plugin_shop']=new Webmodel('plugin_shop');

Webmodel::$model['plugin_shop']->register('name', new CharField(255), 1);

Webmodel::$model['plugin_shop']->register('element', new ChoiceField($size=255, $type='string', $arr_values=array('product', 'cart', 'discounts'), $default_value=''), 1);

Webmodel::$model['plugin_shop']->register('plugin', new ChoiceField($size=255, $type='string', $arr_values=array(''), $default_value=''), 1);

Webmodel::$model['plugin_shop']->register('position', new IntegerField() );

//$arr_plugin_list=array();

//$arr_plugin_list['product'][]='attachments';

//Standard plugins. The user can create her plugins in other files.
class product_attachments extends Webmodel {

	function __construct()
	{

		parent::__construct("product_attachments");

	}
	
	function update($post, $safe_query = 0, $cache_name = '')
	{
	
		$return_file=parent::update($post, $safe_query, $cache_name);
		
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

	function delete()
	{

		//Delete images from field...
		
		$query=$this->select(array('IdProduct_attachments', 'file', 'idproduct'));

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

 		return $this->query('delete from '.$this->name.' '.$self->conditions);
		
	}

}


Webmodel::$model['product_attachments']=new product_attachments();

Webmodel::$model['product_attachments']->register('name', new CharField(255));
Webmodel::$model['product_attachments']->components['name']->required=1;

Webmodel::$model['product_attachments']->register('file', new FileField('file', 'shop/product_attachments/images/', Routes::$root_url.'/shop/product_attachments/images', $type));
Webmodel::$model['product_attachments']->components['file']->required=1;

Webmodel::$model['product_attachments']->register('idproduct', new ForeignKeyField(Webmodel::$model['product'], 11));
Webmodel::$model['product_attachments']->components['idproduct']->required=1;

//Paypal

Webmodel::$model['paypal_check']=new Webmodel('paypal_check');

Webmodel::$model['paypal_check']->register('cookie_shop', new CharField(255), 1);
Webmodel::$model['paypal_check']->register('ckeck', new BooleanField());

//Characteristics example plugin

Webmodel::$model['characteristic']=new Webmodel('characteristic');

Webmodel::$model['characteristic']->register('name', new I18nField(new TextField()), 1);

//The type search in an array in config, can be for example TextForm or ColorForm with a color picker. The value always is text. TextForm is the key, the value is the explain text. If is an array with explain text and library path for load. I can use a table with 3 fields, form, name and path if need load the thing.

Webmodel::$model['characteristic']->register('type', new CharField(255), 1);

//Children of characteristic

Webmodel::$model['characteristic_cat']=new Webmodel('characteristic_cat');

Webmodel::$model['characteristic_cat']->register('idcat', new ForeignKeyField(Webmodel::$model['cat_product']), 1);

Webmodel::$model['characteristic_cat']->register('idcharacteristic', new ForeignKeyField(Webmodel::$model['characteristic']), 1);

Webmodel::$model['characteristic_cat']->components['idcat']->name_field_to_field='title';
Webmodel::$model['characteristic_cat']->components['idcharacteristic']->name_field_to_field='name';

//Webmodel::$model['characteristic']->register('idproduct', new ForeignKeyField('product'), 1);

Webmodel::$model['characteristic_standard_option']=new Webmodel('characteristic_standard_option');

Webmodel::$model['characteristic_standard_option']->register('name', new I18nField(new TextField()), 1);

Webmodel::$model['characteristic_standard_option']->register('added_price', new ShopMoneyField(), 0);

//Webmodel::$model['characteristic_standard_option']->register('characteristic', new ForeignKeyField('characteristic'), 1);

Webmodel::$model['characteristic_standard_option']->register('idcharacteristic', new ForeignKeyField(Webmodel::$model['characteristic']), 1);

Webmodel::$model['characteristic_standard_option']->register('idproduct', new ForeignKeyField(Webmodel::$model['product']), 0);

Webmodel::$model['characteristic_standard_option']->register('position', new IntegerField(), 0);

//Webmodel::$model['characteristic_standard_option']->register('add', new BooleanField(), 0);

Webmodel::$model['characteristic_standard_option']->register('option_delete', new IntegerField(), 0);

//Options for product

/*Webmodel::$model['characteristic_option']=new Webmodel('characteristic_option');

Webmodel::$model['characteristic_option']->register('name', new I18nField(new TextField()), 1);

Webmodel::$model['characteristic_option']->register('characteristic', new ForeignKeyField('characteristic'), 1);

//If false, this option is deleted for standard, if is add, is added to this product. 

Webmodel::$model['characteristic_option']->register('add', new BooleanField(), 0);

Webmodel::$model['characteristic_option']->register('idproduct', new ForeignKeyField('product'), 1);*/

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
		
		Webmodel::$model['plugin_shop']->conditions='where element="'.$this->hook_plugin.'"';
		
		Webmodel::$model['plugin_shop']->order_by='order by position ASC';
		
		$query=Webmodel::$model['plugin_shop']->select(array('plugin'));
		
		while(list($plugin)=Webmodel::$model['plugin_shop']->fetch_row($query))
		{
		
			$class_plugin=ucfirst($plugin).ucfirst($this->hook_plugin).'Class';
			
			$this->arr_plugins[$plugin]=$class_plugin;
			$this->arr_plugin_list[$plugin]=$plugin;
		
		}
	
	}
	
	public function load_plugin($plugin, $arguments=array())
	{
	
		//Utils::load_libraries(array($plugin), PhangoVar::$base_path.'/modules/shop/plugins/'.$plugin.'/'.$this->hook_plugin.'/');
	
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

/*
$arr_module_insert['shop']=array('name' => 'shop', 'admin' => 1, 'admin_script' => array('shop', 'shop'), 'load_module' => '', 'app_index' => 1, 'yes_config' => 1);

$arr_module_sql['shop']='shop.sql';

$arr_module_remove['shop']=array('product', 'image_product', 'cat_product', 'taxes', 'transport', 'price_transport', 'zone_shop', 'country_shop', 'config_shop', 'address_transport', 'payment_form', 'cart_shop', 'order_shop', 'type_product_option', 'product_option', 'group_shop', 'group_shop_users', 'currency', 'currency_change');
*/

?>