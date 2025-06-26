<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Noticia;

class ReporteController extends Controller
{
    private $usuarioModel;
    private $alumnoModel;
    private $cursoModel;
    private $noticiaModel;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar autenticación
        if (!$this->getSession('admin')) {
            $this->redirect('/login');
        }
        
        $this->usuarioModel = new Usuario();
        $this->alumnoModel = new Alumno();
        $this->cursoModel = new Curso();
        $this->noticiaModel = new Noticia();
    }

    public function index()
    {
        $stats = [
            'total_usuarios' => $this->usuarioModel->count(),
            'total_alumnos' => $this->alumnoModel->count(),
            'total_cursos' => $this->cursoModel->count(),
            'total_noticias' => $this->noticiaModel->count()
        ];

        return $this->renderWithLayout('reportes/index', [
            'stats' => $stats
        ]);
    }

    public function usuarios($formato = 'pdf')
    {
        try {
            $usuarios = $this->usuarioModel->findAll();
            
            if ($formato === 'excel') {
                $this->generarExcelUsuarios($usuarios);
            } else {
                $this->generarPDFUsuarios($usuarios);
            }
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al generar reporte: ' . $e->getMessage());
            $this->redirect('/reportes');
        }
    }

    public function alumnos($formato = 'pdf')
    {
        try {
            $alumnos = $this->alumnoModel->getWithUsuario();
            
            if ($formato === 'excel') {
                $this->generarExcelAlumnos($alumnos);
            } else {
                $this->generarPDFAlumnos($alumnos);
            }
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al generar reporte: ' . $e->getMessage());
            $this->redirect('/reportes');
        }
    }

    public function cursos($formato = 'pdf')
    {
        try {
            $cursos = $this->cursoModel->getWithAlumnos();
            
            if ($formato === 'excel') {
                $this->generarExcelCursos($cursos);
            } else {
                $this->generarPDFCursos($cursos);
            }
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al generar reporte: ' . $e->getMessage());
            $this->redirect('/reportes');
        }
    }

    public function noticias($formato = 'pdf')
    {
        try {
            $noticias = $this->noticiaModel->getWithRelations();
            
            if ($formato === 'excel') {
                $this->generarExcelNoticias($noticias);
            } else {
                $this->generarPDFNoticias($noticias);
            }
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al generar reporte: ' . $e->getMessage());
            $this->redirect('/reportes');
        }
    }

    public function alumnoDetalle($id, $formato = 'pdf')
    {
        try {
            $alumno = $this->alumnoModel->getWithUsuarioAndCursos($id);
            if (!$alumno) {
                $this->setSession('error', 'Alumno no encontrado');
                $this->redirect('/reportes');
                return;
            }

            $cursos = $this->alumnoModel->getCursos($id);
            
            if ($formato === 'excel') {
                $this->generarExcelAlumnoDetalle($alumno, $cursos);
            } else {
                $this->generarPDFAlumnoDetalle($alumno, $cursos);
            }
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al generar reporte: ' . $e->getMessage());
            $this->redirect('/reportes');
        }
    }

    // ==================== GENERADORES PDF ====================
    
    private function generarPDFUsuarios($usuarios)
    {
        $html = $this->generarHTMLUsuarios($usuarios);
        $this->outputPDF($html, 'reporte_usuarios.pdf');
    }

    private function generarPDFAlumnos($alumnos)
    {
        $html = $this->generarHTMLAlumnos($alumnos);
        $this->outputPDF($html, 'reporte_alumnos.pdf');
    }

    private function generarPDFCursos($cursos)
    {
        $html = $this->generarHTMLCursos($cursos);
        $this->outputPDF($html, 'reporte_cursos.pdf');
    }

    private function generarPDFNoticias($noticias)
    {
        $html = $this->generarHTMLNoticias($noticias);
        $this->outputPDF($html, 'reporte_noticias.pdf');
    }

    private function generarPDFAlumnoDetalle($alumno, $cursos)
    {
        $html = $this->generarHTMLAlumnoDetalle($alumno, $cursos);
        $this->outputPDF($html, 'reporte_alumno_' . $alumno['id'] . '.pdf');
    }

    // ==================== GENERADORES EXCEL ====================
    
    private function generarExcelUsuarios($usuarios)
    {
        $data = [];
        $data[] = ['ID', 'Nombre', 'Apellido', 'Email', 'Teléfono', 'Dirección', 'Fecha Registro'];
        
        foreach ($usuarios as $usuario) {
            $data[] = [
                $usuario['id'],
                $usuario['nombre'],
                $usuario['apellido'],
                $usuario['email'],
                $usuario['telefono'],
                $usuario['direccion'],
                date('d/m/Y', strtotime($usuario['fecha_creacion']))
            ];
        }
        
        $this->outputExcel($data, 'reporte_usuarios.xlsx');
    }

    private function generarExcelAlumnos($alumnos)
    {
        $data = [];
        $data[] = ['ID', 'Nombre', 'Apellido', 'Edad', 'Familia', 'Cursos', 'Fecha Registro'];
        
        foreach ($alumnos as $alumno) {
            $data[] = [
                $alumno['id'],
                $alumno['nombre'],
                $alumno['apellido'],
                $alumno['edad'],
                $alumno['usuario_nombre'] . ' ' . $alumno['usuario_apellido'],
                $alumno['cursos'] ?? 'Sin cursos',
                date('d/m/Y', strtotime($alumno['fecha_creacion']))
            ];
        }
        
        $this->outputExcel($data, 'reporte_alumnos.xlsx');
    }

    private function generarExcelCursos($cursos)
    {
        $data = [];
        $data[] = ['ID', 'Nombre', 'Nivel', 'Descripción', 'Total Alumnos', 'Fecha Creación'];
        
        foreach ($cursos as $curso) {
            $data[] = [
                $curso['id'],
                $curso['nombre'],
                $curso['nivel'],
                $curso['descripcion'],
                $curso['total_alumnos'],
                date('d/m/Y', strtotime($curso['fecha_creacion']))
            ];
        }
        
        $this->outputExcel($data, 'reporte_cursos.xlsx');
    }

    private function generarExcelNoticias($noticias)
    {
        $data = [];
        $data[] = ['ID', 'Título', 'Contenido', 'Fecha', 'Usuarios', 'Alumnos', 'Cursos'];
        
        foreach ($noticias as $noticia) {
            $data[] = [
                $noticia['id'],
                $noticia['titulo'],
                substr($noticia['contenido'], 0, 100) . '...',
                date('d/m/Y', strtotime($noticia['fecha_creacion'])),
                $noticia['total_usuarios'],
                $noticia['total_alumnos'],
                $noticia['total_cursos']
            ];
        }
        
        $this->outputExcel($data, 'reporte_noticias.xlsx');
    }

    private function generarExcelAlumnoDetalle($alumno, $cursos)
    {
        $data = [];
        $data[] = ['INFORMACIÓN DEL ALUMNO'];
        $data[] = ['ID', $alumno['id']];
        $data[] = ['Nombre', $alumno['nombre']];
        $data[] = ['Apellido', $alumno['apellido']];
        $data[] = ['Edad', $alumno['edad']];
        $data[] = ['Familia', $alumno['usuario_nombre'] . ' ' . $alumno['usuario_apellido']];
        $data[] = ['Fecha Registro', date('d/m/Y', strtotime($alumno['fecha_creacion']))];
        $data[] = [];
        $data[] = ['CURSOS ASIGNADOS'];
        $data[] = ['ID', 'Nombre', 'Nivel', 'Descripción'];
        
        foreach ($cursos as $curso) {
            $data[] = [
                $curso['id'],
                $curso['nombre'],
                $curso['nivel'],
                $curso['descripcion']
            ];
        }
        
        $this->outputExcel($data, 'reporte_alumno_' . $alumno['id'] . '.xlsx');
    }

    // ==================== HELPERS ====================
    
    private function outputPDF($html, $filename)
    {
        // Requerir librería TCPDF o similar
        require_once __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php';
        
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Instituto de Inglés');
        $pdf->SetAuthor('Sistema Administrativo');
        $pdf->SetTitle('Reporte');
        $pdf->SetHeaderData('', 0, 'Instituto de Inglés Roots', 'Reporte Generado');
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 10);
        
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        
        $pdf->Output($filename, 'D');
    }

    private function outputExcel($data, $filename)
    {
        // Requerir librería PhpSpreadsheet
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
            }
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
    }

    // ==================== GENERADORES HTML ====================
    
    private function generarHTMLUsuarios($usuarios)
    {
        $html = '<h1>Reporte de Usuarios</h1>';
        $html .= '<p>Generado el: ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th></tr>';
        
        foreach ($usuarios as $usuario) {
            $html .= '<tr>';
            $html .= '<td>' . $usuario['id'] . '</td>';
            $html .= '<td>' . htmlspecialchars($usuario['nombre']) . '</td>';
            $html .= '<td>' . htmlspecialchars($usuario['apellido']) . '</td>';
            $html .= '<td>' . htmlspecialchars($usuario['email']) . '</td>';
            $html .= '<td>' . htmlspecialchars($usuario['telefono']) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }

    private function generarHTMLAlumnos($alumnos)
    {
        $html = '<h1>Reporte de Alumnos</h1>';
        $html .= '<p>Generado el: ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Edad</th><th>Familia</th></tr>';
        
        foreach ($alumnos as $alumno) {
            $html .= '<tr>';
            $html .= '<td>' . $alumno['id'] . '</td>';
            $html .= '<td>' . htmlspecialchars($alumno['nombre']) . '</td>';
            $html .= '<td>' . htmlspecialchars($alumno['apellido']) . '</td>';
            $html .= '<td>' . $alumno['edad'] . '</td>';
            $html .= '<td>' . htmlspecialchars($alumno['usuario_nombre'] . ' ' . $alumno['usuario_apellido']) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }

    private function generarHTMLCursos($cursos)
    {
        $html = '<h1>Reporte de Cursos</h1>';
        $html .= '<p>Generado el: ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><th>ID</th><th>Nombre</th><th>Nivel</th><th>Alumnos</th></tr>';
        
        foreach ($cursos as $curso) {
            $html .= '<tr>';
            $html .= '<td>' . $curso['id'] . '</td>';
            $html .= '<td>' . htmlspecialchars($curso['nombre']) . '</td>';
            $html .= '<td>' . htmlspecialchars($curso['nivel']) . '</td>';
            $html .= '<td>' . $curso['total_alumnos'] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }

    private function generarHTMLNoticias($noticias)
    {
        $html = '<h1>Reporte de Noticias</h1>';
        $html .= '<p>Generado el: ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><th>ID</th><th>Título</th><th>Fecha</th><th>Destinatarios</th></tr>';
        
        foreach ($noticias as $noticia) {
            $html .= '<tr>';
            $html .= '<td>' . $noticia['id'] . '</td>';
            $html .= '<td>' . htmlspecialchars($noticia['titulo']) . '</td>';
            $html .= '<td>' . date('d/m/Y', strtotime($noticia['fecha_creacion'])) . '</td>';
            $html .= '<td>U:' . $noticia['total_usuarios'] . ' A:' . $noticia['total_alumnos'] . ' C:' . $noticia['total_cursos'] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }

    private function generarHTMLAlumnoDetalle($alumno, $cursos)
    {
        $html = '<h1>Reporte Detallado del Alumno</h1>';
        $html .= '<p>Generado el: ' . date('d/m/Y H:i:s') . '</p>';
        
        $html .= '<h2>Información Personal</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><td><strong>ID:</strong></td><td>' . $alumno['id'] . '</td></tr>';
        $html .= '<tr><td><strong>Nombre:</strong></td><td>' . htmlspecialchars($alumno['nombre']) . '</td></tr>';
        $html .= '<tr><td><strong>Apellido:</strong></td><td>' . htmlspecialchars($alumno['apellido']) . '</td></tr>';
        $html .= '<tr><td><strong>Edad:</strong></td><td>' . $alumno['edad'] . '</td></tr>';
        $html .= '<tr><td><strong>Familia:</strong></td><td>' . htmlspecialchars($alumno['usuario_nombre'] . ' ' . $alumno['usuario_apellido']) . '</td></tr>';
        $html .= '</table>';
        
        $html .= '<h2>Cursos Asignados</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><th>ID</th><th>Nombre</th><th>Nivel</th><th>Descripción</th></tr>';
        
        foreach ($cursos as $curso) {
            $html .= '<tr>';
            $html .= '<td>' . $curso['id'] . '</td>';
            $html .= '<td>' . htmlspecialchars($curso['nombre']) . '</td>';
            $html .= '<td>' . htmlspecialchars($curso['nivel']) . '</td>';
            $html .= '<td>' . htmlspecialchars($curso['descripcion']) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }
} 