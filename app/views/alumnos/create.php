<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Crear Alumno</h2>
    <a href="/alumnos" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Alumno</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errores as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/alumnos" data-validate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" 
                                       required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edad" class="form-label">Edad</label>
                                <input type="number" class="form-control" id="edad" name="edad" 
                                       min="3" max="18" value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label">Familia *</label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccionar familia</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['id'] ?>" 
                                                <?= ($_POST['usuario_id'] ?? '') == $usuario['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                                  placeholder="Información adicional sobre el alumno"><?= htmlspecialchars($_POST['observaciones'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cursos Asignados</label>
                        <div class="row">
                            <?php foreach ($cursos as $curso): ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="cursos[]" value="<?= $curso['id'] ?>" 
                                               id="curso_<?= $curso['id'] ?>"
                                               <?= in_array($curso['id'], $_POST['cursos'] ?? []) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="curso_<?= $curso['id'] ?>">
                                            <?= htmlspecialchars($curso['nombre']) ?> 
                                            <small class="text-muted">(<?= htmlspecialchars($curso['nivel']) ?>)</small>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-text">Selecciona los cursos en los que participará el alumno</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/alumnos" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar Alumno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 