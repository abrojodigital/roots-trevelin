<?php

namespace App\Core;

class Logger
{
    private static $instance = null;
    private $logDir;
    
    private function __construct()
    {
        $this->logDir = __DIR__ . '/../../logs/';
        
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Log de autenticación
     */
    public function logAuth($action, $userId = null, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'details' => $details
        ];
        
        $this->writeLog('auth.log', $logData);
    }
    
    /**
     * Log de operaciones CRUD
     */
    public function logCrud($action, $table, $recordId = null, $userId = null, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'table' => $table,
            'record_id' => $recordId,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'details' => $details
        ];
        
        $this->writeLog('crud.log', $logData);
    }
    
    /**
     * Log de errores
     */
    public function logError($error, $context = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'error' => $error,
            'file' => $context['file'] ?? 'unknown',
            'line' => $context['line'] ?? 'unknown',
            'trace' => $context['trace'] ?? [],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $this->writeLog('errors.log', $logData);
    }
    
    /**
     * Log de notificaciones
     */
    public function logNotification($action, $notificationId = null, $userId = null, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'notification_id' => $notificationId,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'details' => $details
        ];
        
        $this->writeLog('notifications.log', $logData);
    }
    
    /**
     * Log de reportes
     */
    public function logReport($action, $reportType, $userId = null, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'report_type' => $reportType,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'details' => $details
        ];
        
        $this->writeLog('reports.log', $logData);
    }
    
    /**
     * Log de API
     */
    public function logApi($endpoint, $method, $userId = null, $responseCode = null, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'endpoint' => $endpoint,
            'method' => $method,
            'user_id' => $userId,
            'response_code' => $responseCode,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'details' => $details
        ];
        
        $this->writeLog('api.log', $logData);
    }
    
    /**
     * Log de performance
     */
    public function logPerformance($action, $executionTime, $memoryUsage, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'execution_time' => $executionTime,
            'memory_usage' => $memoryUsage,
            'details' => $details
        ];
        
        $this->writeLog('performance.log', $logData);
    }
    
    /**
     * Escribir log en archivo
     */
    private function writeLog($filename, $data)
    {
        $logFile = $this->logDir . $filename;
        $logEntry = json_encode($data) . "\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Obtener logs con filtros
     */
    public function getLogs($type, $filters = [], $limit = 100)
    {
        $logFile = $this->logDir . $type . '.log';
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $logs = [];
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        // Leer líneas en orden inverso (más recientes primero)
        $lines = array_reverse($lines);
        
        foreach ($lines as $line) {
            if (count($logs) >= $limit) break;
            
            $logData = json_decode($line, true);
            if (!$logData) continue;
            
            // Aplicar filtros
            if ($this->applyFilters($logData, $filters)) {
                $logs[] = $logData;
            }
        }
        
        return $logs;
    }
    
    /**
     * Aplicar filtros a los logs
     */
    private function applyFilters($logData, $filters)
    {
        foreach ($filters as $key => $value) {
            if (!isset($logData[$key]) || $logData[$key] != $value) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Limpiar logs antiguos
     */
    public function cleanOldLogs($days = 30)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $logFiles = glob($this->logDir . '*.log');
        
        foreach ($logFiles as $logFile) {
            $this->cleanLogFile($logFile, $cutoffDate);
        }
    }
    
    /**
     * Limpiar archivo de log específico
     */
    private function cleanLogFile($logFile, $cutoffDate)
    {
        if (!file_exists($logFile)) return;
        
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newLines = [];
        
        foreach ($lines as $line) {
            $logData = json_decode($line, true);
            if (!$logData) continue;
            
            if (isset($logData['timestamp']) && $logData['timestamp'] >= $cutoffDate) {
                $newLines[] = $line;
            }
        }
        
        file_put_contents($logFile, implode("\n", $newLines) . "\n");
    }
    
    /**
     * Obtener estadísticas de logs
     */
    public function getLogStats($type = null)
    {
        $stats = [];
        
        if ($type) {
            $logFiles = [$this->logDir . $type . '.log'];
        } else {
            $logFiles = glob($this->logDir . '*.log');
        }
        
        foreach ($logFiles as $logFile) {
            $filename = basename($logFile, '.log');
            
            if (!file_exists($logFile)) {
                $stats[$filename] = [
                    'total_entries' => 0,
                    'last_entry' => null,
                    'file_size' => 0
                ];
                continue;
            }
            
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $totalEntries = count($lines);
            
            $lastEntry = null;
            if ($totalEntries > 0) {
                $lastLine = end($lines);
                $lastLogData = json_decode($lastLine, true);
                $lastEntry = $lastLogData['timestamp'] ?? null;
            }
            
            $stats[$filename] = [
                'total_entries' => $totalEntries,
                'last_entry' => $lastEntry,
                'file_size' => filesize($logFile)
            ];
        }
        
        return $stats;
    }
} 