<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\ImgUploader;
use Illuminate\Support\Facades\File;

class DirectHREmailMailable extends Mailable
{
    use SerializesModels;

    public $job;
    public $user;
    public $profileCv;
    public $coverNote;

    /**
     * Create a new message instance.
     *
     * @param $job
     * @param $user
     * @param $profileCv
     * @param string $coverNote
     */
    public function __construct($job, $user, $profileCv = null, $coverNote = '')
    {
        $this->job = $job;
        $this->user = $user;
        $this->profileCv = $profileCv;
        $this->coverNote = $coverNote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $company = $this->job->getCompany();
        $hrEmail = $this->job->getHrEmail() ?: ($company ? $company->email : config('mail.recieve_to.address'));
        $hrName = $company ? ($company->hr_name ?: $company->name) : 'Hiring Manager';
        $siteName = config('app.name', 'JobnBiz');

        $mail = $this->from(config('mail.from.address', 'noreply@jobnbiz.com'), $this->user->getName() . ' via ' . $siteName)
                     ->replyTo($this->user->email, $this->user->getName())
                     ->to($hrEmail, $hrName)
                     ->subject('Application for ' . $this->job->title . ' - ' . $this->user->getName() . ' (' . $siteName . ')')
                     ->view('emails.direct_hr_application')
                     ->with([
                         'job' => $this->job,
                         'user' => $this->user,
                         'company' => $company,
                         'coverNote' => $this->coverNote,
                         'siteName' => $siteName,
                         'cv' => $this->profileCv
                     ]);

        // Attach Candidate Resume PDF/DOC if present
        if ($this->profileCv && !empty($this->profileCv->cv_file)) {
            $cvFileName = $this->profileCv->cv_file;
            $possiblePaths = [
                public_path('cvs/' . $cvFileName),
                base_path('public/cvs/' . $cvFileName),
                ImgUploader::real_public_path() . 'cvs/' . $cvFileName
            ];

            foreach ($possiblePaths as $path) {
                if (File::exists($path)) {
                    $cleanTitle = \Illuminate\Support\Str::slug($this->user->getName() . '-Resume') . '.' . pathinfo($path, PATHINFO_EXTENSION);
                    $mail->attach($path, [
                        'as' => $cleanTitle,
                        'mime' => mime_content_type($path) ?: 'application/pdf'
                    ]);
                    break;
                }
            }
        }

        return $mail;
    }
}
