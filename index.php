<?php
	include('connection.php'); 	
?>
<html>
	<head>
		<title>Sistema Cadastro de Usuários</title>
		<!---------------------------  Biblioteca JS principal -------------------------------->
		<script src="internet/ajax-googleapis-com-ajax-libs-jquery-2-2-0-jquery-min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />	
		
		<!---------------------------  Biblioteca Bootstrap principal -------------------------------->
		<link rel="stylesheet" href="internet/maxcdn-bootstrapcdn-com-bootstrap-3-3-6-css-bootstrap-min.css" />
		
		<!---------- Plugin datatables ----------------------->
		<script src="internet/cdn-datatables-net-1-10-12-js-jquery-dataTables-min.js"></script>
		<script src="internet/cdn-datatables-net-1-10-12-js-dataTables-bootstrap-min.js"></script>		
		<link rel="stylesheet" href="internet/cdn-datatables-net-1-10-12-css-dataTables-bootstrap-min.css" />	

		<script src="internet/maxcdn-bootstrapcdn-com-bootstrap-3-3-6-js-bootstrap-min.js"></script>
			
		<style>
			body,.modal-dialog {
				margin: 0;
				padding: 0;
				background-color:#666;
				max-width:1300px;
			}
			.box {
				width: 99%;
				padding: 10px;
				background-color: #fafafa;
				border: 1px solid #fafafa;
			}
			.titulo{
				font-weight:bold;
				margin-right:15%;
			}
			.cabecario{
				background-color:#fff;
				padding:10px 10px 10px 0;
			}
			
			/* mostra o x para cancelar ou resetar, dentro do campo pesquisa 
			 no datatable */
			input[type="search"]::-webkit-search-cancel-button{
						-webkit-appearance:searchfield-cancel-button;
					}
					
					@media screen and (min-width: 1000px){
						body, .modal-dialog{/* centraliza o projeto ao ultrapassaro limite tamanho máximo */
						margin:0 auto;	
						}
					}
					.botoes{
						float:right;
					}
					.cabecario{
						background:#fedcba;
						padding:20px;	
					}
					.titulo{
						background:#666;
						color:#fff;
						padding:5px;
						border-radius:10px;	
					}
					thead{
						background:#f1f1f1;	
					}	
		</style>
	</head>	
	<body >
		
	<div id="atualizar" >
		<div class="container col-lg box">
		<!--<h1 align="center">Cadastro de Cliente</h1>-->
			<div class="table-responsive">
				<div align="right" class="cabecario">
					<h1 align="left" ><span class="titulo" >Cadastro de Usuários em Ajax, Php POO e Mysql</span>
						<div class="botoes">
				    <!-- data-backdrop="static" , evita fechar o modal clicando fora do botão fechar -->				
					<button type="button" id="add_button" data-toggle="modal" data-target="#userModal" data-backdrop="static" class="btn btn-info btn-lg" >Adicionar</button>
				</div>
				</h1>
				</div>				
				<br />	
					
				<table id="user_data" class="table table-bordered table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th width="30%"><center>Nome</center></th>
							<th width="20%"><center>Cpf</center></th>
							<th width="20%"><center>Alterar</center></th>
							<th width="19%"><center>Excluir</center></th>
						</tr>
					</thead>
				</table>
				</div>	
				</div>
		</div>
		
		<!-- Formulário -->
<div id="userModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="user_form" >
			<div class="modal-content">
				<div class="modal-header" >
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					 <h4 class="modal-title">Adicionar Cadastro</h4>			 
				</div>
				<div class="modal-body">
						<label>Nome.</label>
						<input type="text" name="first_name" autocomplete="off" id="first_name" maxlength="50" class="form-control title" onKeyup="pri_mai(this);" />				
						<br />												
						<label>Sobrenome.</label>
						<input type="text" name="last_name" id="last_name" maxlength="50" autocomplete="off" class="form-control title" />
						<br />
						<label>CPF.</label>          
						<input type="text" id="cpf" name="cpf" maxlength="11" autocomplete="off" class="cpf_form form-control"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
						<br />
						<label>Endereço.</label>
						<input type="text" name="endereco" id="endereco" autocomplete="off" maxlength="100" class="form-control title" onKeyup="pri_mai(this);" />
						<br />
						<label>Telefone.</label>
						<input type="text" name="telefone" id="telefone" maxlength="16" autocomplete="off" class="form-control" />
						<br />		
						<label>E-mail.</label>
						<input type="email" name="email" id="email" maxlength="50" autocomplete="off" class="form-control" />
						<br />					
					                       			   	
					    <div class="modal-footer">
					 	   <input type="hidden" name="user_id" id="user_id" />
						   <input type="hidden" name="operation" id="operation" />
						   <input type="submit" name="action" id="action" class="btn btn-success" />
						   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
						</div>
				</div>
			</div>
		</form>
	</div>
</div>

       <script type="text/javascript" language="javascript" >
			$(document).ready(function(){
						
// -------------------- Limpa camp ao fechar o modal ----------------------------------------------
				$('.close').click(function(){
					$('.modal').modal('hide');
				});
				
				$('.btn-default').click(function(){
					$('.modal').modal('hide');
				});
				
// -------------------- Botão adicionar ------------------------------------------------------------
				$('#add_button').click(function(){
					$('#user_form')[0].reset();//............................................... Limpa o form ao abrir
					$('.modal-title').text("Adicionar Cadastro");//............................. Muda o nome do título
					$('#action').val("Adicionar");//............................................ Muda o nome do botão
					$('#operation').val("Add");//............................................... Pega o valor do campo operação
			        $("#cpf").val();
			        $('#cpf').css({background:"#fff" }).attr("readonly", false).val();
				    $("#telefone").val();
				    $(".modal-dialog").show();   
				});	
			
// -------------------- Tabela -------------------------------------------------------------------
				var dataTable = $('#user_data').DataTable({
					
					"destroy":true,
					"pageLength":5,// Fixa o valor de linhas por página
					"bLengthChange":false,// Some com as opções de escolha de linhas por página
					"scrollY":false,// Impede scrool horizontal abaixo da tabela
					"processing":true,
					"serverSide":true,
					"order":[0,"asc"],
					"ajax":{
						url:"fetch.php",
						type:"POST"
					},
					"columnDefs":[
						{
							"targets":[2,3],// Colunas que não vão ter setas de ordenação
							"orderable":false,
						},
					],
				});	
			
// -------------------- Tratamento do submit ------------------------------------------------------------				
				$(document).on('submit', '#user_form', function(event){
					event.preventDefault();
					var firstName = $('#first_name').val();
					var lastName = $('#last_name').val();
					var cpf = $('#cpf').val();				
					var endereco = $('#endereco').val();
				    var telefone = $('#telefone').val();
				    var email = $('#email').val();
				    
					if(firstName != '' && lastName != '' && cpf != '' && endereco != '')
					{
						$.ajax({
							url:"operation.php",
							method:'POST',
							data:new FormData(this),
							contentType:false,
							processData:false,
							success:function(data)
							{
								alert(data);                   // Confirmação de sucesso
								$('#user_form')[0].reset();    // Limpa os campos do formulário
								$('#userModal').modal('hide'); // fecha o modal
								$(".modal-dialog").hide().css({width:"39%"});
								dataTable.ajax.reload();       // atualiza a tabela
							}
						});
					}
					else
					{
						alert("Todos os campos são obrigatórios");
					}
				});
				
// -------------------- As funções abaixo acontecem quando clica em Alterar ----------------
				$(document).on('click', '.update', function(){
					var user_id = $(this).attr("id");
					$.ajax({
						url:"fetch_single.php",
						method:"POST",
						data:{user_id:user_id},
						dataType:"json",
						success:function(data)
						{   					
							$('#userModal').modal('show');  // ....................... Mostra o formulário
							$('#first_name').val(data.first_name);  // ............... Pega o valore enviado pelo ajax
							$('#last_name').val(data.last_name);  // ................. Pega o valore enviado pelo ajax
							$('#cpf').val(data.cpf);  // ...................... Pega o valore enviado pelo ajax
							$('#endereco').val(data.endereco);  // ................... Pega o valore enviado pelo ajax
							$('#telefone').val(data.telefone);
							$('#email').val(data.email);  // ................... Pega o valore enviado pelo ajax
							$('.modal-title').text("Editar Cadastro");  //............ Muda o texto do título
							$('#user_id').val(user_id);  // .......................... Pega o valor da id
							$('#action').val("Editar");  // .......................... Muda o valor do botão 
							$('#operation').val("Edit");  // ......................... Muda o texto do botão		
						    $(".modal-dialog").show();
						}
					})
				});
					
// -------------------- Tratamento para excluir cadastro ---------------------------------------		
				$(document).on('click', '.delete', function(){
					var user_id = $(this).attr("id");
					if(confirm("Tem certeza que deseja excluir este?"))
					{
						$.ajax({
							url:"operation.php",
							method:"POST",
							data:{user_id:user_id, operation:'Del'},
							success:function(data)
							{
								alert(data);
								dataTable.ajax.reload();	
							}
						});
					}
					else
					{			
						return false;	
					}
				});
			});
			
// -------------------- Converte a primeira letra da linha em maiúscula 
			function pri_mai(obj){
			    str = obj.value;
			    qtd = obj.value.length;
			    prim = str.substring(0,1);
			    resto = str.substring(1,qtd);
			    str = prim.toUpperCase() + resto;
			    obj.value = str;
			}
			
// -------------------- Converte cada primeira letra de cada palavra em maiúscula
			$.fn.capitalize = function () {
				
			  // Palavras para ser ignoradas
			  var wordsToIgnore = ["to", "and", "the", "it", "or", "that", "this"],
			    minLength = 3;
			
			  function getWords(str) {
			    return str.match(/\S+\s*/g);
			  }
			  this.each(function () {
			    var words = getWords(this.value);
			    $.each(words, function (i, word) {
			      
			      // Somente continua se a palavra nao estiver na lista de ignorados
			      if (wordsToIgnore.indexOf($.trim(word)) == -1 && $.trim(word).length > minLength) {
			        words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
			      }
			    });
			    this.value = words.join("");
			  });
			};
			
			// Onblur do campo com classe .title
			$('.title').on('keyup', function () {
			  $(this).capitalize();
			}).capitalize();
       </script>
	</body>	
</html>