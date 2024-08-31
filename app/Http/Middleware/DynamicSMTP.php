<?php

namespace App\Http\Middleware;

use Swift_SmtpTransport;
use Swift_Mailer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Mail;
use Outerweb\Settings\Models\Setting;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class DynamicSMTP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $smtpSettings = $this->getSMTPSettings();

        // Set mail configuration at runtime
        Config::set('mail.mailer', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtpSettings['host']);
        Config::set('mail.mailers.smtp.port', $smtpSettings['port']);
        Config::set('mail.mailers.smtp.username', $smtpSettings['username']);
        Config::set('mail.mailers.smtp.password', $smtpSettings['password']);
        Config::set('mail.mailers.smtp.encryption', $smtpSettings['encryption']);
        Config::set('mail.from.address', $smtpSettings['from_address']);
        Config::set('mail.from.name', $smtpSettings['from_name']);

        // dd(config());
        // $this->resetMailTransport($smtpSettings);

        return $next($request);
    }

    private function getSMTPSettings()
    {
        $settings = Setting::get('smtp');

        return [
            'host' => $settings['host'] ?? '127.0.0.1',
            'port' => intval($settings['port'] ?? 1025),
            'username' => $settings['username'] ?? '',
            'password' => $settings['password'] ?? '',
            'encryption' => $settings['encryption'] ?? 'tls',
            'from_address' => $settings['from_address'] ?? '',
            'from_name' => $settings['from_name'] ?? '',
        ];
    }

    private function resetMailTransport($smtpSettings)
    {
        $transport = new Swift_SmtpTransport($smtpSettings['host'], $smtpSettings['port'], $smtpSettings['encryption']);
        $transport->setUsername($smtpSettings['username']);
        $transport->setPassword($smtpSettings['password']);

        $mailer = new Swift_Mailer($transport);

        Mail::setSwiftMailer($mailer);
    }
}
