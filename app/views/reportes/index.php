<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Reportes</h2>
    <div>
        <a href="/reportes/estadisticas" class="btn btn-info me-2">
            <i class="bi bi-graph-up"></i> Estadísticas
        </a>
        <a href="/reportes/configuracion" class="btn btn-outline-secondary">
            <i class="bi bi-gear"></i> Configuración
        </a>
    </div>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= $stats['total_usuarios'] ?? 0 ?></h4>
                        <small>Total Familias</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people display-6"></i>
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
                        <h4 class="mb-0"><?= $stats['total_alumnos'] ?? 0 ?></h4>
                        <small>Total Alumnos</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-badge display-6"></i>
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
                        <h4 class="mb-0"><?= $stats['total_cursos'] ?? 0 ?></h4>
                        <small>Total Cursos</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-book display-6"></i>
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
                        <h4 class="mb-0"><?= $stats['total_noticias'] ?? 0 ?></h4>
                        <small>Total Noticias</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-megaphone display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tipos de reportes -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Generar Reportes</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-people display-4 text-primary mb-3"></i>
                                <h5>Reporte de Familias</h5>
                                <p class="text-muted">Listado completo de familias registradas con sus datos de contacto</p>
                                <div class="d-grid gap-2">
                                    <a href="/reportes/usuarios/pdf" class="btn btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                    <a href="/reportes/usuarios/excel" class="btn btn-outline-success">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-person-badge display-4 text-success mb-3"></i>
                                <h5>Reporte de Alumnos</h5>
                                <p class="text-muted">Información detallada de todos los alumnos con sus cursos asignados</p>
                                <div class="d-grid gap-2">
                                    <a href="/reportes/alumnos/pdf" class="btn btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                    <a href="/reportes/alumnos/excel" class="btn btn-outline-success">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-book display-4 text-warning mb-3"></i>
                                <h5>Reporte de Cursos</h5>
                                <p class="text-muted">Detalles de cursos con lista de alumnos inscritos</p>
                                <div class="d-grid gap-2">
                                    <a href="/reportes/cursos/pdf" class="btn btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                    <a href="/reportes/cursos/excel" class="btn btn-outline-success">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card border-info h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-megaphone display-4 text-info mb-3"></i>
                                <h5>Reporte de Noticias</h5>
                                <p class="text-muted">Historial de notificaciones enviadas con estadísticas</p>
                                <div class="d-grid gap-2">
                                    <a href="/reportes/noticias/pdf" class="btn btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                    <a href="/reportes/noticias/excel" class="btn btn-outline-success">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-secondary h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up display-4 text-secondary mb-3"></i>
                                <h5>Reporte de Estadísticas</h5>
                                <p class="text-muted">Análisis completo con gráficos y métricas del instituto</p>
                                <div class="d-grid gap-2">
                                    <a href="/reportes/estadisticas/pdf" class="btn btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                    <a href="/reportes/estadisticas/excel" class="btn btn-outline-success">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-dark h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-event display-4 text-dark mb-3"></i>
                                <h5>Reporte Personalizado</h5>
                                <p class="text-muted">Crear reportes específicos con filtros avanzados</p>
                                <div class="d-grid gap-2">
                                    <a href="/reportes/personalizado" class="btn btn-outline-dark">
                                        <i class="bi bi-gear"></i> Configurar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reportes recientes -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Reportes Recientes</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tipo</th>
                        <th>Formato</th>
                        <th>Generado por</th>
                        <th>Fecha</th>
                        <th>Tamaño</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="badge bg-primary">Familias</span>
                        </td>
                        <td>
                            <i class="bi bi-file-pdf text-danger"></i> PDF
                        </td>
                        <td>Administrador</td>
                        <td><?= date('d/m/Y H:i') ?></td>
                        <td>245 KB</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="#" class="btn btn-outline-primary" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info" title="Vista previa">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="badge bg-success">Alumnos</span>
                        </td>
                        <td>
                            <i class="bi bi-file-excel text-success"></i> Excel
                        </td>
                        <td>Administrador</td>
                        <td><?= date('d/m/Y H:i', strtotime('-1 day')) ?></td>
                        <td>156 KB</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="#" class="btn btn-outline-primary" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info" title="Vista previa">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Configuración de reportes -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Configuración de Reportes</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Configuración de PDF</h6>
                <div class="mb-3">
                    <label class="form-label">Orientación</label>
                    <select class="form-select">
                        <option>Vertical</option>
                        <option>Horizontal</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tamaño de página</label>
                    <select class="form-select">
                        <option>A4</option>
                        <option>Letter</option>
                        <option>Legal</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Configuración de Excel</h6>
                <div class="mb-3">
                    <label class="form-label">Formato de fecha</label>
                    <select class="form-select">
                        <option>dd/mm/yyyy</option>
                        <option>mm/dd/yyyy</option>
                        <option>yyyy-mm-dd</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Incluir gráficos</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Generar gráficos automáticamente</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 