<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RedirectAfterLoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Only run on GET requests
        if (strtolower($request->getMethod()) !== 'get') {
            return;
        }

        $session = session();
        
        // If we already have a beforeLoginUrl in the session, don't overwrite it
        if ($session->has('beforeLoginUrl')) {
            return;
        }

        $referrer = $request->getServer('HTTP_REFERER');

        if ($referrer) {
            $siteUrl = site_url();
            
            // Clean protocols for safe comparison
            $cleanReferrer = preg_replace('/^https?:\/\//i', '', $referrer);
            $cleanSiteUrl = preg_replace('/^https?:\/\//i', '', $siteUrl);
            
            if (strpos($cleanReferrer, $cleanSiteUrl) === 0) {
                // Ignore auth-related URLs
                if (!preg_match('/(login|logout|register|auth\/google|google-auth)/i', $referrer)) {
                    $session->setTempdata('beforeLoginUrl', $referrer, 300);
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
