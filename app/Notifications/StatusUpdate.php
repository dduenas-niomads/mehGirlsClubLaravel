<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusUpdate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('¡Hola, ' . $notifiable->first_name . '!')
                    ->line('Un nuevo cupón ha sido creado')
                    ->line('Descripción: ' . $notifiable->description)
                    ->action($notifiable->code, url('#'))
                    ->line('Úsalo antes de finalizar tu compra online o en la tienda física.');
                    ->line('Gracias por ser parte del Meh Girls Club.');
                    ->line('Si tienes alguna duda por favor consulta nuestras Preguntas frecuentes o escríbenos respondiendo a este correo (loyalty@mehperu.com) o por Whatsapp al +51 952 928 928 indicando el número de tu orden de compra o código de cupon.');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
