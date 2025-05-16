<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotificacionCorreo extends Notification
{
    use Queueable;

    public string $titulo;
    public string $mensaje;

    public string $rutaAdjunto;

    public function __construct(string $titulo, string $mensaje, ?string $rutaAdjunto = null)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->rutaAdjunto = $rutaAdjunto;
    }


    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->titulo)
            ->line($this->mensaje)
            ->line('Gracias por usar nuestra aplicación.');

        if ($this->rutaAdjunto && file_exists($this->rutaAdjunto)) {
            $mailMessage->attach($this->rutaAdjunto, [
                'as' => 'archivo.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $mailMessage;
    }
}
