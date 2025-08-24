<?php
require_once('../conexao.php');
require_once('verificar.php');
$pag = 'cursos';

if (@$_SESSION['nivel'] != 'Administrador' and @$_SESSION['nivel'] != 'Professor') {
	echo "<script>window.location='../index.php'</script>";
	exit();
}
?>


<button onclick="inserir()" type="button" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i>Novo Curso</button>


<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>





<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="tituloModal"></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="form-niceEdit">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Nome</label>
								<input type="text" class="form-control" name="nome" id="nome" required>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Subtítulo</label>
								<input type="text" class="form-control" name="desc_rapida" id="desc_rapida">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Categoria</label>
								<select class="form-control sel2" name="categoria" id="categoria" required style="width:100%;">
									<?php
									$query = $pdo->query("SELECT * FROM categorias order by nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}
									?>
										<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Grupo</label>
								<select class="form-control sel2" name="grupo" id="grupo" required style="width:100%;">
									<?php
									$query = $pdo->query("SELECT * FROM grupos order by nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}
									?>
										<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Valor</label>

								<input type="text" class="form-control" name="valor" id="valor">
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Carga</label>
								<input type="text" class="form-control" name="carga" id="carga" placeholder="Horas">
							</div>
						</div>

						<div class="col-md-8">
							<div class="form-group">
								<label>Palavras-chaves</label>
								<input type="text" class="form-control" name="palavras" id="palavras" placeholder="Ex: Curso de programação, desenvolvimento web, etc...">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-8 col-sm-12">
							<div class="form-group">
								<label>Descrição do Curso</label>
								<textarea name="desc_longa" id="area" class="textarea"></textarea>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Imagem</label>
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>

							<div id="divImg">
								<img src="img/cursos/sem-foto.png" width="130px" id="target">
							</div>

						</div>

					</div>



					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label>Pacote Link URL</label>
								<input type="text" class="form-control" name="pacote" id="pacote" placeholder="Link do pacote de cursos">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Técnologias Usadas</label>
								<input type="text" class="form-control" name="tecnologias" id="tecnologias" placeholder="Ex: Html, Css, Banco de dados Mysql etc...">
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Sistema (Fontes)</label>
								<select class="form-control" name="sistema" id="sistema">
									<option value="Não">Não</option>
									<option value="Sim">Sim</option>
								</select>
							</div>
						</div>


					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label>Arquivo <small>(Link Material Apoio)</small></label>
								<input type="text" class="form-control" name="arquivo" id="arquivo" placeholder="Link para baixar os arquivos">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Link do curso</label>
								<input type="text" class="form-control" name="link" id="link" placeholder="Caso disponibiliza para download, colocar o link">
							</div>
						</div>

					</div>




					<br>
					<input type="hidden" name="id" id="id">
					<small>
						<div id="mensagem" align="center" class="mt-3"></div>
					</small>
				</div>


				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- ModalMostrar -->
<div class="modal fade" id="modalMostrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="tituloModal"><span id="nome_mostrar"> </span> - Status : <span id="status_mostrar"> </span></h4>
				<button id="btn-fechar-excluir" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">



				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-5">
						<span><b>Subtítulo: </b></span>
						<span id="desc_rapida_mostrar"></span>
					</div>
					<div class="col-md-2">
						<span><b>Valor: </b></span>
						<span id="valor_mostrar"></span>
					</div>
					<div class="col-md-5">
						<span><b>Professor: </b></span>
						<span id="professor_mostrar"></span>
					</div>

				</div>


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-4">
						<span><b>Categoria: </b></span>
						<span id="categoria_mostrar"></span>
					</div>
					<div class="col-md-3">
						<span><b>Grupo: </b></span>
						<span id="grupo_mostrar"></span>
					</div>
					<div class="col-md-3">
						<span><b>Carga: </b></span>
						<span id="carga_mostrar"></span> Horas
					</div>
					<div class="col-md-2">
						<span><b>Ano: </b></span>
						<span id="ano_mostrar"></span>
					</div>
				</div>


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-12">
						<span><b>Palavras Chaves: </b></span>
						<span id="palavras_mostrar"></span>
					</div>
				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-4">
						<span><b>Pacote: </b></span>
						<a target="_blank" href="" id="linkpacote"> <span id="pacote_mostrar"></span> </a>
					</div>
					<div class="col-md-2">
						<span><b>Sistema: </b></span>
						<span id="sistema_mostrar"></span>
					</div>
					<div class="col-md-6">
						<span><b>Tecnologias: </b></span>
						<span id="tecnologias_mostrar"></span>
					</div>
				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-12">
						<span><b>Link Arquivos (Material Apoio Drive): </b></span>
						<a target="_blank" href="" id="linkarquivo"> <span id="arquivo_mostrar"></span> </a>
					</div>
				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-12">
						<span><b>Link do curso (Drive): </b></span>
						<a target="_blank" href="" id="linkcurso"> <span id="link_mostrar"></span> </a>
					</div>

				</div>

				<div class="row">
					<div class="col-md-8">
						<span><b>Descrição do Curso: </b></span>
						<small><span id="desc_longa_mostrar"></span></small> </a>
					</div>

					<div class="col-md-4" align="center">
						<img width="200px" id="target_mostrar">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Mensagem -->
<div class="modal fade" id="modalMensagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="nome_mensagem"> </span></h4>
				<button id="btn-fechar-mensagem" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-mensagem">
				<div class="modal-body">

					<div class="form-group">
						<label>Mensagem do Curso</label>
						<textarea class="textarea" name="mensagem" id="mensagem_mensagem"></textarea>

					</div>

					<input type="hidden" name="id" id="id_mensagem">





				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>

			</form>


		</div>
	</div>
</div>


<!-- Modal Aulas -->
<div class="modal fade" id="modalAulas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="nome_aula_titulo"> </span> - <span id="aulas_aula"> </span> Aulas </h4>
				<button id="btn-fechar-aula" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<form id="form-aulas">

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>Num Aula</label>
										<input type="number" name="num_aula" id="num_aula" class="form-control" required>
									</div>
								</div>

								<div class="col-md-9">
									<div class="form-group">
										<label>Nome Aula</label>
										<input type="text" name="nome_aula" id="nome_aula" class="form-control" required>
									</div>
								</div>
							</div>

							
								<div class="col-md-12">
									<div class="form-group">
										<label>Link <small>(Url Incorporada ou Link do Drive)</small></label>
										<input type="text" name="link_aula" id="link_aula" class="form-control">
									</div>

								<div class="col-md-9">
									<div class="form-group">
										<label>Nome Sessão <small>(caso exista)</small></label>
										<input type="text" name="sessao_aula" id="sessao_aula" class="form-control" placeholder="Ex: básico, módulo 1, etc">
									</div>
								</div>

								<div class="col-md-3">
									<button type="submit" class="btn btn-primary" style="margin-top:21px;">Salvar</button>
								</div>
							</div>

							<input type="hidden" name="id" id="id-aulas">
							<input type="hidden" name="id_aula" id="id-da-aula">

							<small>
								<div id="mensagem_aulas" align="center" class="mt-3"></div>
							</small>
						</form>

					</div>

					<div class="col-md-6">
						<div id="listar-aulas"></div>
					</div>
				</div>



			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>


<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#foto").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>

<script type="text/javascript">
	$("#form-niceEdit").submit(function() {
		event.preventDefault();
		nicEditors.findEditor('area').saveContent();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/inserir.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {
					$('#btn-fechar').click();
					listar();
				} else {
					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});



	});
</script>


<script type="text/javascript">
	$("#form-mensagem").submit(function() {
		event.preventDefault();
		nicEditors.findEditor('mensagem_mensagem').saveContent();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/mensagem.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem_msg').text('');
				$('#mensagem_msg').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {
					$('#btn-fechar-mensagem').click();
					listar();
				} else {
					$('#mensagem_msg').addClass('text-danger')
					$('#mensagem_msg').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>

<script type="text/javascript">
	function listarAulas() {
		var id_curso = $('#id-aulas').val();
		$.ajax({
			url: 'paginas/' + pag + "/listar-aulas.php",
			method: 'POST',
			data: {
				id_curso
			},
			dataType: "text",

			success: function(result) {
				$("#listar-aulas").html(result);
				$('#mensagem-excluir-aulas').text('');
				$('#mensagem-aulas').text('');
				limparCamposAulas();
			}
		});
	}
</script>

<script type="text/javascript">
	$("#form-aulas").submit(function() {
		event.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: 'paginas/' + pag + "/inserir-aulas.php",
			type: 'POST',
			data: formData,
			success: function(mensagem) {
				$('#mensagem_aulas').text('');
				$('#mensagem_aulas').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {
					//$('#btn-fechar').click();
					listarAulas();
				} else {
					$('#mensagem_aulas').addClass('text-danger')
					$('#mensagem_aulas').text(mensagem)
				}
			},
			cache: false,
			contentType: false,
			processData: false,
		});
	});
</script>

<script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(nicEditors.allTextAreas);
</script>