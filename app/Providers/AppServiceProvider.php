<?php

namespace App\Services;

class TcpNotificacionService
{
    public function enviarDatosTcp($receptor, $titulo, $mensajes)
    {
        $host = env('MICROSERVICIO_NODE_HOST');
        $port = env('MICROSERVICIO_NODE_PORT');

        $fp = stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 5);

        if (!$fp) {
            return "Error al conectar: $errstr ($errno)";
        }

        $mensaje = json_encode([
            'receptor' => $receptor,
            'titulo' => $titulo,
            'mensaje' => $mensajes,
        ]);

        fwrite($fp, $mensaje . "\n");

        $respuesta = fgets($fp, 1024);

        fclose($fp);

        return "Respuesta del microservicio TCP: " . trim($respuesta);
    }
}
