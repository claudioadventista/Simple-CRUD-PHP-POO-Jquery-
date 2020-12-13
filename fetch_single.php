<?php	// Página responsável para carregar os dados para o formulário para alteração

	include('connection.php');
	$connection = BancoDados::conectarW();
	if(isset($_POST["user_id"]))
	{
		$output = array();
		$statement = $connection->prepare(
			"SELECT * FROM users 
			WHERE id = '".$_POST["user_id"]."' 
			LIMIT 1"
		);
		$statement->execute();
		$result = $statement->fetchAll();
		
		foreach($result as $row)
		{
					$output["first_name"] = $row["first_name"];
					$output["last_name"] = $row["last_name"];
					$output["cpf"] = $row["cpf"];
					$output["endereco"] = $row["endereco"];
					$output["telefone"] = $row["telefone"];
					$output["email"] = $row["email"];
		}
		echo json_encode($output);
	}
?>