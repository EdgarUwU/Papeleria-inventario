<div class="container is-fluid mb-6">
	<h1 class="title">Usuarios</h1>
	<h2 class="subtitle">Nuevo usuario</h2>
</div>
<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/usuario_guardar.php" method="POST" class="FormularioAjax" autocomplete="off">
		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Nombre</label>
					<span>*</span>
					<input class="input" type="text" name="nombre" maxlength="45" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Apellido Paterno</label>
					<input class="input" type="text" name="apellido_pat" maxlength="45">
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Apellido Materno</label>
					<input class="input" type="text" name="apellido_mat" maxlength="45">
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Usuario</label>
					<span>*</span>
					<input class="input" type="text" name="username" pattern="[a-zA-Z0-9]{4,20}" maxlength="45" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Contraseña</label>
					<span>*</span>
					<input class="input" type="password" name="contrasena" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Repetir Contraseña</label>
					<span>*</span>
					<input class="input" type="password" name="contrasena2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
				</div>
			</div>
		</div>
		<div class="column">
			<div class="control">
				<label>Permiso</label>
				<span>*</span><br>
				<div class="select is-rounded">
					<select name="permiso_usuario">
						<option value="" selected="">Seleccione una opción</option>
						<option value="1">Administrador</option>
						<option value="3">Normal</option>
						<option value="2">Auditor</option>
					</select>
				</div>
			</div>
		</div>
		<div class="column">
			<div class="control">
				<label>Foto</label><br>
				<div class="file is-small has-name">
					<label class="file-label">
						<input class="file-input" type="file" name="usuario_foto" accept=".jpg, .png, .jpeg">
						<span class="file-cta">
							<span class="file-label">Buscar</span>
						</span>
						<span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
					</label>
				</div>
			</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>