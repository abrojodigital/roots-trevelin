<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detalle del Alumno</h2>
    <div>
        <a href="/alumnos/edit/<?= $alumno['id'] ?>" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="/alumnos" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> <?= htmlspecialchars($alumno['nombre']) ?></p>
                        <p><strong>Apellido:</strong> <?= htmlspecialchars($alumno['apellido']) ?></p>
                        <p><strong>Edad:</strong> <?= $alumno['edad'] ? $alumno['edad'] . ' años' : 'No especificada' ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Familia:</strong> 
                            <?php if (isset($alumno['usuario_nombre'])): ?>
                                <?= htmlspecialchars($alumno['usuario_nombre'] . ' ' . $alumno['usuario_apellido']) ?>
                            <?php else: ?>
                                <span class="text-muted">Sin familia asignada</span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Fecha de Registro:</strong> 
                            <?= date('d/m/Y', strtotime($alumno['fecha_creacion'])) ?>
                        </p>
                    </div>
                </div>
                
                <?php if (!empty($alumno['observaciones'])): ?>
                    <div class="mt-3">
                        <strong>Observaciones:</strong>
                        <p class="mt-2"><?= nl2br(htmlspecialchars($alumno['observaciones'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Cursos Asignados</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($cursos)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($cursos as $curso): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($curso['nombre']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($curso['nivel']) ?></small>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    <i class="bi bi-book"></i>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No tiene cursos asignados</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="/alumnos/edit/<?= $alumno['id'] ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i> Editar Información
                    </a>
                    <a href="/noticias?alumno_id=<?= $alumno['id'] ?>" class="btn btn-outline-info">
                        <i class="bi bi-megaphone"></i> Ver Noticias
                    </a>
                    <a href="/reportes/alumno/<?= $alumno['id'] ?>" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-text"></i> Generar Reporte
                    </a>
                    <a href="/alumnos/delete/<?= $alumno['id'] ?>" 
                       class="btn btn-outline-danger btn-delete"
                       onclick="return confirm('¿Estás seguro de que deseas eliminar este alumno?')">
                        <i class="bi bi-trash"></i> Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 