<?php
require_once __DIR__ . '/enviar_onesignal_base.php';

function enviarNoticiaGeneral($titulo, $contenido) {
    return enviarOneSignal($titulo, $contenido, ['included_segments' => ['Subscribed Users']]);
}