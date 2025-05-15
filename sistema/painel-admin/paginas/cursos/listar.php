<?php
require_once("../../../conexao.php");
$tabela = 'cursos';

echo <<<HTML
<small>
HTML;

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
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

		//FORMATAR VALORES
		$valorF = number_format($valor, 2, ',', '.');
		$desc_longa = str_replace('"', "**", $desc_longa);

		
		echo <<<HTML
<tr> 
		<td><img src="img/cursos/{$foto}" width="27px" class="mr-2">
		{$nome}
		</td> 
		<td class="esc">
		R$ {$valorF}
		</td>
		<td class="esc">{$nome_professor}</td>		
		<td class="esc">{$nome_categoria}</td>
		<td class="esc">0</td>
		<td class="esc">0</td>	
		<td>
		<big><a href="#" onclick="editar('{$id}', '{$nome}', '{$desc_rapida}','{$desc_longa}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

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

	function editar(id, nome, descricao, foto) {

		$('#id').val(id);
		$('#nome').val(nome);
		$('#descricao').val(descricao);

		$('#foto').val('');
		$('#target').attr('src', 'img/categorias/' + foto);

		$('#tituloModal').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#mensagem').text('');
	}


	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#descricao').val('');
		$('#foto').val('');
		$('#target').attr('src', 'img/categorias/sem-foto.png');
	}
</script>