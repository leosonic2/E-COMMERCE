<?php 

	namespace Hcode\Model;

	use \Hcode\Model;
	use \Hcode\DB\Sql;
	use \Hcode\Mailer;
	use \Hcode\Model\User;

	class Cart extends Model {

		const SESSION = "Cart";

		public static function getFromSession()
		{

			$cart = new Cart();

			if(isset($_SESSION[Cart::SESSION]) && $_SESSION[Cart::SESSION]['idcart']>0)
			{
				$cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
			}else
			{
				$cart->getFromSessionID();

				if(!(int)$cart->getidcart()>0)
				{
					$data = [
						'dessessionid'=>session_id()
					];

					if(User::checkLogin(false))
					{
						$user = User::getFromSession();

						$data['iduser'] = $user->getiduser();
					}

					$cart->setData($data);

					$cart->save();

					$cart->setToSession();


				}

			}
			return $cart;
		}//fim do método getFromSession

		public function setToSession()
		{
			$_SESSION[Cart::SESSION] = $this->getValues();
		}//Fim do método setToSession

		public function getFromSessionID()
		{
			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid = :dessessionid",[

				':dessessionid'=>session_id()
			]);

			if(count($results)>0)
			{
				$this->setData($results[0]);
			}

		}//Fim do método getFromSessionID()

		public function get(int $idcart)
		{
			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart",[

				':idcart'=>$idcart
			]);

			if(count($results)>0)
			{
				$this->setData($results[0]);
			}
			

		}//Fim do método get()

		public function save()
		{

			$sql = new Sql();

			$results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)",[

				':idcart'=>$this->getidcart(),
				':dessessionid'=>$this->getdessessionid(),
				':iduser'=>$this->getiduser(),
				':deszipcode'=>$this->getdeszipcode(),
				':vlfreight'=>$this->vlfreight(),
				':nrdays'=>$this->nrdays()

			]);

			$this->setData($results[0]);
		}// Fim do método save


	}

 ?>