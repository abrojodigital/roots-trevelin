<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Alumnos</h2>
    <a href="/alumnos/create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nuevo Alumno
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/alumnos" class="row g-3">
            <div class="col-md-3">
                <label for="buscar" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="buscar" name="buscar" 
                       placeholder="Nombre o apellido" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <label for="usuario_id" class="form-label">Familia</label>
                <select class="form-select" id="usuario_id" name="usuario_id">
                    <option value="">Todas las familias</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario['id'] ?>" <?= $usuarioId == $usuario['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="curso_id" class="form-label">Curso</label>
                <select class="form-select" id="curso_id" name="curso_id">
                    <option value="">Todos los cursos</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?= $curso['id'] ?>" <?= $cursoId == $curso['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($curso['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="/alumnos" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de alumnos -->
<div class="card">
    <div class="card-body">
        <?php if (empty($alumnos['data'])): ?>
            <div class="text-center py-4">
                <i class="bi bi-person-badge display-1 text-muted"></i>
                <h4 class="mt-3">No se encontraron alumnos</h4>
                <p class="text-muted">No hay alumnos que coincidan con los criterios de búsqueda.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Familia</th>
                            <th>Edad</th>
                            <th>Cursos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos['data'] as $alumno): ?>
                            <tr>
                                <td><?= $alumno['id'] ?></td>
                                <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                                <td><?= htmlspecialchars($alumno['apellido']) ?></td>
                                <td>
                                    <?php if (isset($alumno['usuario_nombre'])): ?>
                                        <?= htmlspecialchars($alumno['usuario_nombre'] . ' ' . $alumno['usuario_apellido']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin familia asignada</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $alumno['edad'] ?? 'N/A' ?></td>
                                <td>
                                    <?php if (isset($alumno['cursos']) && !empty($alumno['cursos'])): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($alumno['cursos']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Sin cursos</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/alumnos/view/<?= $alumno['id'] ?>" 
                                           class="btn btn-outline-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/alumnos/edit/<?= $alumno['id'] ?>" 
                                           class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/alumnos/delete/<?= $alumno['id'] ?>" 
                                           class="btn btn-outline-danger btn-delete" title="Eliminar"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este alumno?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if (isset($alumnos['totalPages']) && $alumnos['totalPages'] > 1): ?>
                <nav aria-label="Paginación de alumnos">
                    <ul class="pagination justify-content-center">
                        <?php if ($alumnos['page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $alumnos['page'] - 1 ?>&buscar=<?= urlencode($search) ?>&usuario_id=<?= $usuarioId ?>&curso_id=<?= $cursoId ?>">
                                    Anterior
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $alumnos['totalPages']; $i++): ?>
                            <li class="page-item <?= $i == $alumnos['page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&buscar=<?= urlencode($search) ?>&usuario_id=<?= $usuarioId ?>&curso_id=<?= $cursoId ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($alumnos['page'] < $alumnos['totalPages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $alumnos['page'] + 1 ?>&buscar=<?= urlencode($search) ?>&usuario_id=<?= $usuarioId ?>&curso_id=<?= $cursoId ?>">
                                    Siguiente
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div> 