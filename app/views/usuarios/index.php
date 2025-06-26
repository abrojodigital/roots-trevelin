<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Usuarios (Familias)</h2>
    <a href="/usuarios/create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/usuarios" class="row g-3">
            <div class="col-md-4">
                <label for="buscar" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="buscar" name="buscar" 
                       placeholder="Nombre o apellido" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="/usuarios" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="card">
    <div class="card-body">
        <?php if (empty($usuarios)): ?>
            <div class="text-center py-4">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3">No se encontraron usuarios</h4>
                <p class="text-muted">No hay usuarios que coincidan con los criterios de búsqueda.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= $usuario['id'] ?></td>
                                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                                <td><?= htmlspecialchars($usuario['direccion']) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/usuarios/view/<?= $usuario['id'] ?>" 
                                           class="btn btn-outline-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/usuarios/edit/<?= $usuario['id'] ?>" 
                                           class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/usuarios/delete/<?= $usuario['id'] ?>" 
                                           class="btn btn-outline-danger btn-delete" title="Eliminar"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
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
            <?php if (isset($usuarios['totalPages']) && $usuarios['totalPages'] > 1): ?>
                <nav aria-label="Paginación de usuarios">
                    <ul class="pagination justify-content-center">
                        <?php if ($usuarios['page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $usuarios['page'] - 1 ?>&buscar=<?= urlencode($search) ?>">
                                    Anterior
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $usuarios['totalPages']; $i++): ?>
                            <li class="page-item <?= $i == $usuarios['page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&buscar=<?= urlencode($search) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($usuarios['page'] < $usuarios['totalPages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $usuarios['page'] + 1 ?>&buscar=<?= urlencode($search) ?>">
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