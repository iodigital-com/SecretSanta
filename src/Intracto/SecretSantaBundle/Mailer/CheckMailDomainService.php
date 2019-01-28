<?php

namespace Intracto\SecretSantaBundle\Mailer;

class CheckMailDomainService
{
    private $blackListedDomains = [
        '@hotmail.',
        '@live.',
        '@msn.',
        '@outlook.',
        '@windowslive.',
    ];

    private $blacklistedMxRecords = [
        'mail.protection.outlook.com',
    ];

    /**
     * @param string $emailAddress
     *
     * @return bool
     */
    public function isBlacklistedAddress(string $emailAddress): bool
    {
        if ($this->isBlacklistedDomain($emailAddress) || $this->isBlacklistedMxRecord($emailAddress)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $emailAddress
     *
     * @return bool
     */
    private function isBlacklistedMxRecord(string $emailAddress): bool
    {
        $blacklisted = false;

        $domainName = substr(strrchr($emailAddress, '@'), 1);
        $mxRecords = getmxrr($domainName, $mxhosts);

        if ($mxRecords) {
            foreach ($mxhosts as $mxHost) {
                foreach ($this->blacklistedMxRecords as $blacklistedMxRecord) {
                    //check if mx ends with one of the blacklisted ones
                    if (substr_compare($mxHost, $blacklistedMxRecord, strlen($mxHost) - strlen($blacklistedMxRecord), strlen($blacklistedMxRecord)) === 0) {
                        $blacklisted = true;
                    }
                }
            }
        }

        return $blacklisted;
    }

    /**
     * @param string $emailAddress
     *
     * @return bool
     */
    private function isBlacklistedDomain(string $emailAddress): bool
    {
        foreach ($this->blackListedDomains as $blackListedDomain) {
            if (strpos($emailAddress, $blackListedDomain) !== false) {
                return true;
            }
        }

        return false;
    }
}
