<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;

class ProyectoEvaluadoNotification extends Notification
{
    use Queueable;

    public $proyecto;

    /**
     * Crea una nueva instancia de la notificación.
     *
     * @param Project $proyecto
     */
    public function __construct(Project $proyecto)
    {
        $this->proyecto = $proyecto;
    }

    /**
     * Obtener los canales de notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Usamos el canal de correo electrónico
    }

    /**
     * Construir el mensaje de correo para la notificación.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tu Proyecto ha sido Evaluado')
            ->line('El proyecto "' . $this->proyecto->nombre . '" ha sido evaluado por tu profesor.')
            ->line('Puedes ver los comentarios y la evaluación en el portal.')
            ->action('Ver Proyecto', url(route('projects.show', $this->proyecto->id)))
            ->line('Gracias por tu esfuerzo y dedicación.');
    }
}
