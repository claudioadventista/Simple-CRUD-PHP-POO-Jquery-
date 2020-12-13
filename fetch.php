<?php	# Página responsável para informar os dados da tabela

	include('connection.php');
	$connection = BancoDados::conectarW();
	
	$query = '';
	
	# Busca por ocorrência
	$query .= "SELECT * FROM users ";
	if(isset($_POST["search"]["value"]))
	{
		$query .= 'WHERE first_name LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR last_name LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR  cpf LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR  endereco LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR  telefone LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR  email LIKE "%'.$_POST["search"]["value"].'%" ';
	}
	
	# Conta o resultado no rodapé da tabela
		if(isset($_POST["search"]["value"]))
	{
		$busca = $_POST["search"]["value"];
		$statem = $connection->prepare("SELECT * FROM users WHERE first_name LIKE '%$busca%' OR last_name LIKE '%$busca%' OR cpf LIKE '%$busca%' OR endereco LIKE '%$busca%' OR telefone LIKE '%$busca%' OR email LIKE '%$busca%'");
	    $statem->execute();
		$filtro = $statem->rowCount();
		
		// Conta o total de entradas no banco
		$entradas = $connection->prepare("SELECT * FROM users");
	    $entradas->execute();
		$total_de_entradas= $entradas->rowCount();
	}
		
	# Ordenação da tabela 
	$columns = array( 
		0 => 'first_name', 
		1 => 'cpf',
		
	); 
	
	if(isset($_POST["order"]))
	{
		$query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
	}
	else
	{
		$query .= 'ORDER BY id DESC ';
	}
	if($_POST["length"] != -1)
	{
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}
	
	$statement = $connection->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$data = array();
	
	foreach($result as $row)
	{		
		$sub_array = array();
		
		$sub_array[] = '<span id="'.$row["id"].'" class="btn btn-light btn-xs update">'.$row["first_name"].'</span>';
		$sub_array[] = '<span id="'.$row["id"].'" class="btn btn-light btn-xs update">'.$row["cpf"].'</span>';
		$sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update"style="margin-left:25%;width:50%;padding:5px 20px">Alterar</button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete" style="margin-left:25%;width:50%;padding:5px 20px">Excluir</button>';
		
		$data[] = $sub_array;
	}
	
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$total_de_entradas,	# Total de registro na tabela
		"recordsFiltered"	=>	$filtro,	# Filtra os registro encontrados, elimina páginas no rodapé,
		"data"				=>	$data
	);
	
	echo json_encode($output);
?>