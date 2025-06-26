<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Notificaciones</h2>
    <div>
        <a href="/notificaciones/estadisticas" class="btn btn-info me-2">
            <i class="bi bi-graph-up"></i> Estadísticas
        </a>
        <a href="/notificaciones/create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nueva Notificación
        </a>
    </div>
</div>

<!-- Tarjetas de estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= $stats['total_notificaciones'] ?? 0 ?></h4>
                        <small>Total Notificaciones</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-megaphone display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= $stats['notificaciones_hoy'] ?? 0 ?></h4>
                        <small>Hoy</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-day display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= $stats['notificaciones_semana'] ?? 0 ?></h4>
                        <small>Esta Semana</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-week display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= $stats['notificaciones_mes'] ?? 0 ?></h4>
                        <small>Este Mes</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-month display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tipos de notificación -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Enviar Notificación Rápida</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="bi bi-globe display-4 text-primary mb-3"></i>
                                <h5>General</h5>
                                <p class="text-muted">Para todos los usuarios</p>
                                <a href="/notificaciones/general" class="btn btn-outline-primary">
                                    Enviar General
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="bi bi-book display-4 text-success mb-3"></i>
                                <h5>Por Curso</h5>
                                <p class="text-muted">Para alumnos de un curso específico</p>
                                <a href="/notificaciones/curso" class="btn btn-outline-success">
                                    Enviar por Curso
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="bi bi-people display-4 text-warning mb-3"></i>
                                <h5>Por Familia</h5>
                                <p class="text-muted">Para una familia específica</p>
                                <a href="/notificaciones/familia" class="btn btn-outline-warning">
                                    Enviar por Familia
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="bi bi-person display-4 text-info mb-3"></i>
                                <h5>Personalizada</h5>
                                <p class="text-muted">Seleccionar destinatarios</p>
                                <a href="/notificaciones/create" class="btn btn-outline-info">
                                    Crear Personalizada
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notificaciones recientes -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Notificaciones Recientes</h5>
        <a href="/notificaciones/historial" class="btn btn-outline-secondary btn-sm">
            Ver Historial Completo
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($notificaciones)): ?>
            <div class="text-center py-4">
                <i class="bi bi-megaphone display-1 text-muted"></i>
                <h4 class="mt-3">No hay notificaciones</h4>
                <p class="text-muted">Aún no se han enviado notificaciones.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Destinatarios</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notificaciones as $notificacion): ?>
                            <tr>
                                <td><?= $notificacion['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($notificacion['titulo']) ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars(substr($notificacion['contenido'], 0, 50)) ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $this->getTipoColor($notificacion['tipo']) ?>">
                                        <?= ucfirst($notificacion['tipo']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($notificacion['fecha_creacion'])) ?>
                                </td>
                                <td>
                                    <?php if ($notificacion['tipo'] === 'general'): ?>
                                        <span class="badge bg-primary">Todos</span>
                                    <?php elseif ($notificacion['tipo'] === 'curso'): ?>
                                        <span class="badge bg-success">Curso</span>
                                    <?php elseif ($notificacion['tipo'] === 'familia'): ?>
                                        <span class="badge bg-warning">Familia</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Personalizada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Enviada
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/notificaciones/view/<?= $notificacion['id'] ?>" 
                                           class="btn btn-outline-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/notificaciones/duplicate/<?= $notificacion['id'] ?>" 
                                           class="btn btn-outline-secondary" title="Duplicar">
                                            <i class="bi bi-files"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Función helper para colores de tipos
function getTipoColor(tipo) {
    const colors = {
        'general': 'primary',
        'curso': 'success',
        'familia': 'warning',
        'personalizada': 'info'
    };
    return colors[tipo] || 'secondary';
}
</script> 