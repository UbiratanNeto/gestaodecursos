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
			<form method="post" id="form">
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

						<div class="col-md-4">
							<div class="form-group">
								<label>Pacote Link URL</label>
								<input type="text" class="form-control" name="pacote" id="pacote" placeholder="Link do pacote de cursos">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Técnologias Usadas</label>
								<input type="text" class="form-control" name="tecnologia" id="tecnologia" placeholder="Ex: Html, Css, Banco de dados Mysql etc...">
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
								<img src="img/cursos/sem-foto.png" width="140px" id="target">
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


<script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
				