<?php

class external_attachments extends Webmodel {

	function __construct()
	{

		parent::__construct("external_attachments");

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

$model['external_attachments']=new external_attachments();

$model['external_attachments']->components['name']=new CharField(255);
$model['external_attachments']->components['name']->required=1;

$model['external_attachments']->components['file']=new FileField('file', $base_path.'/modules/shop/files/', '');
$model['external_attachments']->components['file']->required=1;

$model['external_attachments']->components['idproduct']=new ForeignKeyField('product', 11);
$model['external_attachments']->components['idproduct']->required=1;

?>