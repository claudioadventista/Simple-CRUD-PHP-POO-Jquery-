<?php	# Página responsável para inserir, editar e excluir cadastro

	include('connection.php');
	$connection = BancoDados::conectarW();
	
	if(isset($_POST["operation"]))
	{
		
# Adicionar novo cadastro -----------------------------------------------------------
		if($_POST["operation"] == "Add")
		{	
			$statement = $connection->prepare("
				INSERT INTO users (first_name, last_name, cpf, endereco, telefone, email) 
				VALUES (:first_name, :last_name, :cpf, :endereco, :telefone, :email)
			");
			$result = $statement->execute(
				array(
					':first_name'	=>	$_POST["first_name"],
					':last_name'	=>	$_POST["last_name"],
					':cpf'	        =>	$_POST["cpf"],
					':endereco'	    =>	$_POST["endereco"],
					':telefone'	    =>	$_POST["telefone"],
					':email'	    =>	$_POST["email"]
				)
			);
			if(!empty($result))
			{
				echo 'Novo Cadastro Inserido com Sucesso';
			}
		}
		
# Editar cadastro ---------------------------------------------------------------
		if($_POST["operation"] == "Edit")
		{
			$statement = $connection->prepare(
				"UPDATE users 
				SET first_name = :first_name, last_name = :last_name, cpf = :cpf, endereco = :endereco, telefone = :telefone, email = :email 
				WHERE id = :id
				"
			);
			$result = $statement->execute(
				array(
					':first_name'	=>	$_POST["first_name"],
					':last_name'	=>	$_POST["last_name"],
					':cpf'	        =>	$_POST["cpf"],
					':endereco'	    =>	$_POST["endereco"],
					':telefone'	    =>	$_POST["telefone"],
					':email'	    =>	$_POST["email"],
					':id'			=>	$_POST["user_id"]
				)
			);
			if(!empty($result))
			{
				echo 'Cadastro Atualizado com Sucesso';
			}
		}
		
# Excluir cadastro ---------------------------------------------------------------
		if($_POST["operation"] == "Del")
		{
				if(isset($_POST["user_id"]))
				{
					$statement = $connection->prepare(
						"DELETE FROM users WHERE id = :id"
					);
					$result = $statement->execute(
						array(
							':id'	=>	$_POST["user_id"]
						)
					);
					
					if(!empty($result))
					{
						echo 'Cadastro Excluído com Sucesso';
					}
				}		
		}	
	}
?>