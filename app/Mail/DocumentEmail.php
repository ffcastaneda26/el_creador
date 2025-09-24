<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $documentType;
    public $pdfContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($documentType, $pdfContent)
    {
        $this->documentType = $documentType;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->documentType,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            htmlString: "Anexo al presente se adjunta el documento del {$this->documentType}.
            <br><br>
            Por favor no dude en contactarnos si tiene algún comentario, duda o sugerencia.
            <br><br>
            Atentamente
            <br><br>
            El Creador",
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, "documento_{$this->documentType}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
