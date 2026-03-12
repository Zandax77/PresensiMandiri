<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\PengajuanIjin;

class IzinDiajukan extends Notification
{
    use Queueable;

    protected $pengajuan;

    /**
     * Create a new notification instance.
     *
     * @param  PengajuanIjin  $pengajuan
     */
    public function __construct(PengajuanIjin $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // in-app only (database); could add mail later
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $siswa = $this->pengajuan->user;
        return [
            'message' => "Siswa {$siswa->name} mengajukan izin {$this->pengajuan->jenis_izin} dari {$this->pengajuan->tanggal_awal} sampai {$this->pengajuan->tanggal_akhir}.",
            'link'    => route('izin.index', ['status' => 'menunggu']),
            'pengajuan_id' => $this->pengajuan->id,
        ];
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
                    ->line('Ada pengajuan izin baru dari siswa ' . ($this->pengajuan->user->name ?? ''))
                    ->action('Lihat Pengajuan', route('izin.index', ['status' => 'menunggu']))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }
}
