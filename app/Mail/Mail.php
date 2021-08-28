<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Request instance.
     *
     * @var Order
     */
    protected $body;

    /**
     * Template instance.
     *
     * @var PersonName
     */
    protected $template;

    /**
     * Person Name instance.
     *
     * @var PersonName
     */
    protected $personName;

    /**
     * Subject Name Instance.
     *
     * @var subjectName
     */
    protected $subjectName;

    /**
     * Create a new message instance.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param  \App\Models\Template  $template
     *
     * @param  string $personName
     *
     * @return void
     */
    public function __construct($body, $template, $personName,$subject)
    {
        $this->body = $body;
        $this->template = $template;
        $this->personName = $personName;
        $this->subjectName = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subjectName)
            ->view('vendor.backpack.base.email.templates.template')
            ->with('body',$this->body)
            ->with('template', $this->template)
            ->with('personName',$this->personName);
    }
}
