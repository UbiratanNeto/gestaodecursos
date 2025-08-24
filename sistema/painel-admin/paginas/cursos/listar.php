<?php
require_once("../../../conexao.php");
$tabela = 'cursos';

@session_start();
if($_SESSION['nivel'] == 'Administrador'){
	$acesso = '';
	$id_usuario = '%%';
}else{
	$acesso = 'ocultar';
	$id_usuario = '%'.$_SESSION['id'].'%';
}
echo <<<HTML
<small>
HTML;

$query = $pdo->query("SELECT * FROM $tabela where professor LIKE '$id_usuario' ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	echo <<<HTML
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>
	<th class="esc">Valor</th> 
	<th class="esc">Professor</th>
	<th class="esc">Categoria</th>
	<th class="esc">Alunos</th>
	<th class="esc">Aulas</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>
HTML;

	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$id = $res[$i]['id'];
		$nome = $res[$i]['nome'];
		$desc_rapida = $res[$i]['desc_rapida'];
		$desc_longa = $res[$i]['desc_longa'];
		$valor = $res[$i]['valor'];
		$professor = $res[$i]['professor'];
		$categoria = $res[$i]['categoria'];
		$foto = $res[$i]['imagem'];
		$status = $res[$i]['status'];
		$carga = $res[$i]['carga'];
		$mensagem = $res[$i]['mensagem'];
		$arquivo = $res[$i]['arquivo'];
		$ano = $res[$i]['ano'];
		$palavras = $res[$i]['palavras'];
		$grupo = $res[$i]['grupo'];
		$nome_url = $res[$i]['nome_url'];
		$pacote = $res[$i]['pacote'];
		$sistema = $res[$i]['sistema'];
		$link = $res[$i]['link'];
		$tecnologias = $res[$i]['tecnologias'];


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$professor'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome_professor = $res2[0]['nome'];

		$query2 = $pdo->query("SELECT * FROM categorias where id = '$categoria'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome_categoria = $res2[0]['nome'];

		$query2 = $pdo->query("SELECT * FROM grupos where id = '$grupo'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome_grupo = $res2[0]['nome'];

		$query2 = $pdo->query("SELECT * FROM aulas where curso = '$id'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$aulas = @count($res2);

		if ($status == 'Aprovado') {
			$excluir = 'ocultar';
			$icone = 'fa-check-square';
			$titulo_link = 'Desaprovar Curso';
			$acao = 'Aguardando';
			$classe_linha = '';
			$classe_square = 'verde';
		} else {
			$excluir = '';
			$icone = 'fa-square-o';
			$titulo_link = 'Aprovar Curso';
			$acao = 'Aprovado';
			$classe_linha = 'text-muted';
			$classe_square = 'text-danger';
		}

		if($mensagem != ''){
			$classe_mensagem = 'warning';
		}else{
			$classe_mensagem = 'text-warning';
		}
		//FORMATAR VALORES
		$valorF = number_format($valor, 2, ',', '.');
		$desc_longa = str_replace('"', "**", $desc_longa);


		echo <<<HTML
<tr class="{$classe_linha}"> 
		<td><img src="img/cursos/{$foto}" width="27px" class="mr-2">
		<a href="#" onclick="aulas('{$id}', '{$nome}', '{$aulas}')" class="cinza_escuro">
		{$nome}
		<small><i class="fa fa-video-camera text-dark"></i></small>
		</a>
		</td> 
		<td class="esc">
		R$ {$valorF}
		</td>
		<td class="esc">{$nome_professor}</td>		
		<td class="esc">{$nome_categoria}</td>
		<td class="esc">0</td>
		<td class="esc">{$aulas}</td>
		<td>
		<big><a href="#" onclick="editar('{$id}', '{$nome}', '{$desc_rapida}', '{$desc_longa}', '{$valor}', '{$categoria}', '{$foto}', '{$carga}' , '{$arquivo}', '{$ano}', '{$palavras}','{$grupo}', '{$pacote}','{$sistema}', '{$link}' ,'{$tecnologias}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$desc_rapida}','{$desc_longa}','{$valorF}','{$nome_professor}','{$nome_categoria}','{$foto}','{$status}', '{$carga}', '{$arquivo}', '{$ano}', '{$palavras}', '{$nome_grupo}', '{$pacote}', '{$sistema}', '{$link}', '{$tecnologias}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>

		<li class="dropdown head-dpdn2 {$excluir}" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

		<big><a class="{$acesso}" href="#" onclick="ativar('{$id}', '{$acao}')" title="{$titulo_link}"><i class="fa {$icone} $classe_square"></i></a></big>

		<big><a href="#" onclick="obs('{$id}', '{$nome}', '{$mensagem}')" title="Ver Mensagens"><i class="fa fa-comment-o {$classe_mensagem}"></i></a></big>

		</td>
</tr>
HTML;
	}

	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>	
HTML;
} else {
	echo 'Não possui nenhum registro cadastrado!';
}
echo <<<HTML
</small>
HTML;


?>


<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true,
		});
		$('#tabela_filter label input').focus();
	});

	function editar(id, nome, desc_rapida, desc_longa, valor, categoria, foto, carga, arquivo, ano, palavras, grupo, pacote, sistema, link, tecnologias) {

	for (let letra of desc_longa){  				
			if (letra === '*'){
				desc_longa = desc_longa.replace('**', '"');
			}			
		}	

		$('#id').val(id);
		$('#nome').val(nome);
		$('#desc_rapida').val(desc_rapida);
		nicEditors.findEditor("area").setContent(desc_longa);	
		$('#valor').val(valor);
		$('#categoria').val(categoria).change();		
		$('#carga').val(carga);
		$('#arquivo').val(arquivo);
		$('#ano').val(ano);
		$('#palavras').val(palavras);
		$('#grupo').val(grupo).change();	;
		$('#pacote').val(pacote);
		$('#link').val(link);
		$('#sistema').val(sistema).change();	;
		$('#tecnologias').val(tecnologias);
	
		$('#foto').val('');
		$('#target').attr('src','img/cursos/' + foto);		
		
		$('#tituloModal').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#mensagem').text('');
	}


	
	function mostrar(nome, desc_rapida, desc_longa, valor, professor, categoria, foto, status, carga, arquivo, ano, palavras, grupo, pacote, sistema, link, tecnologias){	
		
		$('#nome_mostrar').text(nome);
		$('#desc_rapida_mostrar').text(desc_rapida);
		$('#desc_longa_mostrar').html(desc_longa);
		$('#valor_mostrar').text(valor);
		$('#professor_mostrar').text(professor);
		$('#categoria_mostrar').text(categoria);
		$('#status_mostrar').text(status);
		$('#carga_mostrar').text(carga);
		$('#arquivo_mostrar').text(arquivo);
		$('#ano_mostrar').text(ano);
		$('#palavras_mostrar').text(palavras);
		$('#grupo_mostrar').text(grupo);
		$('#pacote_mostrar').text(pacote);	
		$('#sistema_mostrar').text(sistema);	
		$('#link_mostrar').text(link);			
		$('#tecnologias_mostrar').text(tecnologias);	
		$('#target_mostrar').attr('src','img/cursos/' + foto);

		$('#linkpacote').attr('href','<?=$url_sistema?>' + pacote);
		$('#linkcurso').attr('href', link);
		$('#linkarquivo').attr('href', arquivo);

		$('#modalMostrar').modal('show');
		
	}

	function obs(id, nome, mensagem){	
		
		$('#nome_mensagem').text(nome);
		$('#id_mensagem').val(id);
		nicEditors.findEditor("mensagem_mensagem").setContent(mensagem);

		$('#modalMensagem').modal('show');
		
	}


		function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#desc_rapida').val('');
		nicEditors.findEditor("area").setContent('');				
		$('#valor').val('');	
		$('#carga').val('');	
		$('#palavras').val('');	
		$('#pacote').val('');	
		$('#tecnologias').val('');	
		$('#arquivo').val('');	
		$('#link').val('');		
		nicEditors.findEditor("mensagem_mensagem").setContent('');		
		$('#foto').val('');
		$('#target').attr('src','img/cursos/sem-foto.png');		
	}

	function aulas(id, nome, aulas){
		$('#id-aulas').val(id);
		$('#nome_aula_titulo').text(nome);
		$('#modalAulas').modal('show');
		listarAulas();
	}
</script>