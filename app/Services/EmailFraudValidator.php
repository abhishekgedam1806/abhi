<?php

namespace App\Services;

use App\BlockedEmailDomain;

class EmailFraudValidator
{
    /**
     * Built-in list of popular disposable / temporary / burner email domains
     */
    protected static $disposableDomains = [
        'mailinator.com', '10minutemail.com', '10minutemail.net', 'tempmail.com', 'tempmail.net',
        'temp-mail.org', 'guerrillamail.com', 'guerrillamail.net', 'guerrillamail.org', 'guerrillamailblock.com',
        'sharklasers.com', 'grr.la', 'yopmail.com', 'yopmail.fr', 'yopmail.net',
        'trashmail.com', 'trashmail.net', 'trashmail.org', 'dispostable.com', 'fakemailgenerator.com',
        'getairmail.com', 'mohmal.com', 'emailondeck.com', 'burnermail.io', 'crazymailing.com',
        'generator.email', 'tempinbox.com', 'throwawaymail.com', 'inboxkitten.com', 'nada.ltd',
        'getnada.com', 'mytrashmail.com', 'maildrop.cc', 'harakirimail.com', 'tmail.ws',
        'tempail.com', 'minutemailbox.com', 'trashmail.me', 'deadaddress.com', 'trashymail.com',
        'mytemp.email', 'instantemailaddress.com', 'dropmail.me', 'fakeinbox.com', 'mailcatch.com',
        'spambog.com', 'spamgourmet.com', 'disposablemail.com', 'meltmail.com', 'guerrillamail.biz',
        'guerrillamail.de', 'pokemail.net', 'spamfree24.org', 'jetable.org', 'kasmail.com',
        '0-mail.com', '10mail.org', '20minutemail.com', '33mail.com', 'anonaddy.com',
        'boximail.com', 'chacuo.net', 'courrieltemporaire.com', 'crazymail.com', 'cryptomail.com',
        'dayrep.com', 'discard.email', 'discardmail.com', 'discardmail.de', 'disposable.com',
        'einrot.com', 'emailfree.org', 'emailgo.de', 'emailmiser.com', 'emailproxsy.com',
        'emailsensei.com', 'emailtemporaneo.com', 'emltmp.com', 'fackme.gq', 'fake-box.com',
        'fakeinformation.com', 'fakemail.net', 'fakermail.com', 'fastchever.com', 'fastmailbox.net',
        'filzmail.com', 'fleckens.hu', 'frapmail.com', 'garbagemail.org', 'ghostmailer.net',
        'gishpuppy.com', 'gustr.com', 'hidemail.de', 'hushmail.me', 'incognitomail.org',
        'jourrapide.com', 'junkmail.com', 'laste.ml', 'lazyinbox.com', 'link2mail.net',
        'loadby.us', 'lookugly.com', 'lortemail.dk', 'mamber.net', 'meatspin.com',
        'mega.zik.dj', 'megamail.pt', 'moncourrier.fr', 'monemail.fr', 'msr.anonaddy.com',
        'mytempemail.com', 'netcourrier.com', 'nobulk.com', 'noclickemail.com', 'nomail.xl.cx',
        'nospam.ze.tc', 'nospam4.us', 'notsharingmy.info', 'nowmymail.com', 'nullbox.info',
        'objectmail.com', 'oneoffmail.com', 'onewaymail.com', 'owlpic.com', 'pookmail.com',
        'privacy.net', 'proxymail.eu', 'quickinbox.com', 'rcpt.at', 'rhyta.com',
        'safersignup.com', 'safetymail.info', 'sandflow.com', 'sendit.nodeposit.me', 'shieldedmail.com',
        'shortmail.com', 'sibmail.com', 'slopsbox.com', 'smartinbox.co.uk', 'sneakemail.com',
        'sofort-mail.de', 'sogetthis.com', 'soodonims.com', 'spamavert.com', 'spambox.us',
        'spamcon.org', 'spamcowboy.com', 'spamday.com', 'spamex.com', 'spamhole.com',
        'spaminator.de', 'spammotel.com', 'spamslicing.com', 'spamspot.com', 'spamtrail.com',
        'superstachel.de', 'teleworm.us', 'temp-mail.ru', 'tempemail.co', 'tempemail.net',
        'tempinbox.co.uk', 'tempthe.net', 'thetempmail.com', 'trash-mail.at', 'trash-mail.com',
        'trash-me.com', 'trashmail.at', 'trashmail.net', 'trashmailer.com', 'trbvm.com',
        'tuamaeaquelaursa.com', 'uggsrock.com', 'urhen.com', 'valemail.net', 'veryrealemail.com',
        'vmani.com', 'wegwerfadresse.de', 'wegwerfemail.de', 'wegwerfmail.de', 'wegwerfmail.net',
        'wegwerfmail.org', 'wh4f.org', 'whyspam.me', 'willhackforfood.biz', 'wuzup.net',
        'xagloo.com', 'xemaps.com', 'xents.com', 'yep.it', 'yogamaven.com',
        'zehnminutenmail.de', 'zippymail.info', 'zoemail.org'
    ];

    /**
     * Validate an email for anti-fraud, syntax, and legitimacy.
     *
     * @param string $email
     * @return array ['valid' => bool, 'reason' => string|null]
     */
    public static function validate($email)
    {
        $email = trim(strtolower($email));

        // 1. Basic format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'reason' => 'Please enter a valid email address format.'
            ];
        }

        // 2. Extract domain
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return [
                'valid' => false,
                'reason' => 'Invalid email address structure.'
            ];
        }

        $user = $parts[0];
        $domain = $parts[1];

        // 3. Username checks (length and suspicious patterns)
        if (strlen($user) < 2) {
            return [
                'valid' => false,
                'reason' => 'Email username is too short.'
            ];
        }

        // 4. Check against built-in disposable list
        if (in_array($domain, self::$disposableDomains)) {
            return [
                'valid' => false,
                'reason' => 'Temporary, disposable, or burner email addresses are not permitted. Please use a legitimate email (@gmail.com, company email, etc.).'
            ];
        }

        // 5. Check against database custom blocked domains
        try {
            $isCustomBlocked = BlockedEmailDomain::where('domain', $domain)
                ->where('is_active', 1)
                ->exists();
            if ($isCustomBlocked) {
                return [
                    'valid' => false,
                    'reason' => 'This email domain has been restricted for security reasons. Please use another email.'
                ];
            }
        } catch (\Exception $e) {
            // Fallback if DB table check fails
        }

        // 6. Check DNS MX records for company / custom domains
        // Standard trusted public providers skip DNS query for performance
        $trustedDomains = [
            'gmail.com', 'googlemail.com', 'yahoo.com', 'yahoo.in', 'yahoo.co.uk',
            'outlook.com', 'hotmail.com', 'live.com', 'msn.com', 'icloud.com', 'me.com',
            'aol.com', 'zoho.com', 'protonmail.com', 'proton.me', 'rediffmail.com'
        ];

        if (!in_array($domain, $trustedDomains)) {
            // Verify domain has valid mail server (MX) or host (A) records
            if (function_exists('checkdnsrr')) {
                $hasMx = @checkdnsrr($domain, 'MX');
                $hasA  = @checkdnsrr($domain, 'A');
                if (!$hasMx && !$hasA) {
                    return [
                        'valid' => false,
                        'reason' => "The email domain '{$domain}' does not appear to have an active mail server. Please verify your company email address."
                    ];
                }
            }
        }

        return [
            'valid' => true,
            'reason' => null
        ];
    }
}
