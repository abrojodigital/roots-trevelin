<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Cursos</h2>
    <a href="/cursos/create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nuevo Curso
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/cursos" class="row g-3">
            <div class="col-md-4">
                <label for="buscar" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="buscar" name="buscar" 
                       placeholder="Nombre del curso" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <label for="nivel" class="form-label">Nivel</label>
                <select class="form-select" id="nivel" name="nivel">
                    <option value="">Todos los niveles</option>
                    <option value="Principiante" <?= $nivel == 'Principiante' ? 'selected' : '' ?>>Principiante</option>
                    <option value="Intermedio" <?= $nivel == 'Intermedio' ? 'selected' : '' ?>>Intermedio</option>
                    <option value="Avanzado" <?= $nivel == 'Avanzado' ? 'selected' : '' ?>>Avanzado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="/cursos" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de cursos -->
<div class="card">
    <div class="card-body">
        <?php if (empty($cursos['data'])): ?>
            <div class="text-center py-4">
                <i class="bi bi-book display-1 text-muted"></i>
                <h4 class="mt-3">No se encontraron cursos</h4>
                <p class="text-muted">No hay cursos que coincidan con los criterios de búsqueda.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Nivel</th>
                            <th>Descripción</th>
                            <th>Alumnos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos['data'] as $curso): ?>
                            <tr>
                                <td><?= $curso['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($curso['nombre']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $curso['nivel'] == 'Principiante' ? 'success' : ($curso['nivel'] == 'Intermedio' ? 'warning' : 'danger') ?>">
                                        <?= htmlspecialchars($curso['nivel']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($curso['descripcion'])): ?>
                                        <?= htmlspecialchars(substr($curso['descripcion'], 0, 50)) ?>
                                        <?= strlen($curso['descripcion']) > 50 ? '...' : '' ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin descripción</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($curso['total_alumnos']) && $curso['total_alumnos'] > 0): ?>
                                        <span class="badge bg-info"><?= $curso['total_alumnos'] ?> alumnos</span>
                                    <?php else: ?>
                                        <span class="text-muted">Sin alumnos</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/cursos/view/<?= $curso['id'] ?>" 
                                           class="btn btn-outline-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/cursos/edit/<?= $curso['id'] ?>" 
                                           class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/cursos/delete/<?= $curso['id'] ?>" 
                                           class="btn btn-outline-danger btn-delete" title="Eliminar"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?')">
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
            <?php if (isset($cursos['totalPages']) && $cursos['totalPages'] > 1): ?>
                <nav aria-label="Paginación de cursos">
                    <ul class="pagination justify-content-center">
                        <?php if ($cursos['page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $cursos['page'] - 1 ?>&buscar=<?= urlencode($search) ?>&nivel=<?= $nivel ?>">
                                    Anterior
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $cursos['totalPages']; $i++): ?>
                            <li class="page-item <?= $i == $cursos['page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&buscar=<?= urlencode($search) ?>&nivel=<?= $nivel ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($cursos['page'] < $cursos['totalPages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $cursos['page'] + 1 ?>&buscar=<?= urlencode($search) ?>&nivel=<?= $nivel ?>">
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