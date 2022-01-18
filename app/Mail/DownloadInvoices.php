<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class DownloadInvoices extends Mailable
{
    // use Queueable, SerializesModels;

    public $file_path;

    public $company;

    public function __construct($file_path, Company $company)
    {
        $this->file_path = $file_path;

        $this->company = $company;
    }

    /**
     * Build the message.
     */
    public function build()
    {

        App::setLocale($this->company->getLocale());

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(ctrans('texts.download_files'))
            ->view('email.admin.download_invoices', [
                'url' => $this->file_path,
                'logo' => $this->company->present()->logo,
                'whitelabel' => $this->company->account->isPaid() ? true : false,
                'settings' => $this->company->settings,
                'greeting' => $this->company->present()->name(),
            ]);
    }
}