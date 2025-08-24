<?php
require_once("../../../conexao.php");
$tabela = 'aulas';

$id_curso = $_POST['id_curso'];

echo <<<HTML
<small>
HTML;

$query = $pdo->query("SELECT * FROM $tabela where curso = '$id_curso' ORDER BY num_aula desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$ultima_aula = $res[0]['num_aula'] + 1;
$total_reg = @count($res);
if ($total_reg > 0) {
	echo <<<HTML
	<small><table class="table table-hover" id="tabela2">
	<thead> 
	<tr> 
	<th>Aula</th> 
	<th class="">Nome</th>
    <th class="esc">Link</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>
HTML;

	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value){}
		$id = $res[$i]['id'];
		$nome = $res[$i]['nome'];
        $num_aula = $res[$i]['num_aula'];
        $link = $res[$i]['link'];
		$sessao = $res[$i]['sessao'];

        $linkF = mb_strimwidth($link, 0, 15, "...");



		echo <<<HTML
<tr> 
		<td>
		{$num_aula}	
		</td>
		<td class="esc">{$nome}</td>
        <td class="esc"><a title="{$link}" href="{$link}" target="_blank">{$linkF}</a></td>	
		
		<td>
		<big><a href="#" onclick="editarAula('{$id}', '{$num_aula}', '{$nome}', '{$link}', '{$sessao}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirAula('{$id}')"><span class="text-danger">Sim</span></a></p>
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
<small><div align="center" id="mensagem-excluir-aulas"></div></small>
</table>
</small>
HTML;
} else {
	echo 'Não possui nenhuma aula cadastrada!';
}
echo <<<HTML
</small>
HTML;


?>


<script type="text/javascript">
	$(document).ready(function() {
		
	});

	function editarAula(id, aula, nome, link, sessao){

		$('#id-da-aula').val(id);
		$('#link_aula').val(link);
		$('#nome_aula').val(nome);
		$('#num_aula').val(aula);
	}


	function limparCamposAulas() {
		$('#id-da-aula').val('');
		$('#link_aula').val('');
		$('#nome_aula').val('');
		$('#num_aula').val('<?=$ultima_aula?>');
		$('#aulas_aula').text('<?=$total_reg?>');
		
	}

	function excluirAula(id) {
    $.ajax({
        url: 'paginas/' + pag + "/excluir-aulas.php",
        method: 'POST',
        data: { id },
        dataType: "text",

        success: function (mensagem) {
            if (mensagem.trim() == "Excluído com Sucesso") {
                listarAulas();
            } else {
                $('#mensagem-excluir-aulas').addClass('text-danger')
                $('#mensagem-excluir-aulas').text(mensagem)
            }

        },

    });
}


</script>