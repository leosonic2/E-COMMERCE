<?php 

	namespace Hcode\Model;

	use \Hcode\Model;
	use \Hcode\DB\Sql;
	use \Hcode\Mailer;

	class Product extends Model {

		
		public static function listALL()
		{

			$sql = new Sql();

			return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
		
		}// fim do método listALL

		public static function checkList($list)
		{
			foreach ($list as &$row) {
				
				$p = new Product();
				$p->setData($row);
				$row = $p->getValues();
			}
			return $list;
		
		}// fim do método checkList

		public function save()
		{
			$sql = new Sql();
			$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength,:vlweight, :desurl)", array(
				":idproduct"=>$this->getidproduct(),
				":desproduct"=>utf8_decode($this->getdesproduct()),
				":vlprice"=>$this->getvlprice(),
				":vlwidth"=>$this->getvlwidth(),
				":vlheight"=>$this->getvlheight(),
				":vllength"=>$this->getvllength(),
				":vlweight"=>$this->getvlweight(),
				":desurl"=>$this->getdesurl()
				
			));
			
			$this->setData($results[0]);
			
		}// Fim do método save		
		
		public function get($idproduct)
		{

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [
				"idproduct" => $idproduct
			]);

			$this->setData($results[0]);
		
		}//fim do método get

		public function delete()
		{
			$sql = new Sql();

			$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [
				"idproduct" => $this->getidproduct()
			]);

			
		}//fim do método delete()


		public function checkPhoto()
		{
			if (file_exists(
				$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
				"res" . DIRECTORY_SEPARATOR . 
				"site" . DIRECTORY_SEPARATOR . 
				"img" . DIRECTORY_SEPARATOR . 
				"products" . DIRECTORY_SEPARATOR . 
				$this->getidproduct() . ".jpg"
				)) {
				$url = "/res/site/img/products/" . $this->getidproduct() . ".jpg";
			} else {
				$url = "/res/site/img/product.jpg";
			}
			return $this->setdesphoto($url);
		
		}// Fim do método checkPhoto

		public function getValues()
		{

			$this->checkPhoto();
			
			$values = parent::getValues();


			return $values;
		}// Fim do método getValues

		public function setPhoto($file)
		{
			$extension = explode('.',$file['name']);
			$extension = end($extension);

			switch($extension)
			{
				case "jpg":
				$image = imagecreatefromjpeg($file["tmp_name"]);
				break;

				case "jpeg":
				$image = imagecreatefromjpeg($file["tmp_name"]);
				break;

				case "gif":
				$image = imagecreatefromgif($file["tmp_name"]);
				break;

				case "png":
				$image = imagecreatefrompng($file["tmp_name"]);
				break;

			}//Fim do método setPhoto

			$destino = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.
				"res".DIRECTORY_SEPARATOR.
				"site".DIRECTORY_SEPARATOR.
				"img".DIRECTORY_SEPARATOR.
				"products".DIRECTORY_SEPARATOR.
				$this->getidproduct().".jpg";	
			imagejpeg($image,$destino);
			imagedestroy($image);

			$this->checkPhoto();

		}// fim do método setPhoto

		public function getFromURL($desurl)
		{
			$sql = new Sql();

			$rows =  $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1",[
				':desurl'=>$desurl,
			]);

			$this->setData($rows[0]);
		
		}// fim do método getFromURL

		public function getCategories()
		{
			$sql = new Sql();
			return $sql->select("
				SELECT * FROM tb_categories a INNER JOIN tb_categoriesproducts b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct
			", [
				':idproduct'=>$this->getidproduct()
			]);
		}//fim do método getCategories

	}

 ?>