<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Juzaweb\Modules\Admin\Networks\Facades\Network;
use Juzaweb\Modules\Core\Support\Encryption;

class VerifyToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('app.verify_token', true)) {
            return $next($request);
        }

        $token = $request->input('jw-token');

        if (!$token || !str_contains($token, '.')) {
            return response()->json(['error' => 'Invalid token format'], 401);
        }

        [$encodedSalt, $encryptedData] = explode('.', $token, 2);

        try {
            $salt = urldecode(base64_decode($encodedSalt));
            $csrf = $request->header('X-CSRF-TOKEN');
            $websiteId = Network::website()->id;

            if (!$csrf || !$websiteId) {
                return response()->json(['error' => 'Missing CSRF or website ID'], 401);
            }

            $recaptcha = hash('sha256', config('app.key') . Network::website()->id);
            $nonceValue = $websiteId . substr($csrf, 9, 16) . substr($recaptcha, 0, 10);
            $decrypted = $this->decryptData($encryptedData, $nonceValue);

            if (!$decrypted || !str_contains($decrypted, '-')) {
                return response()->json(['error' => 'Invalid token data'], 401);
            }

            [$hash, $datetime] = explode('-', $decrypted);

            if ($hash !== hash('sha256', $salt . $csrf)) {
                return response()->json(['error' => 'Token hash mismatch'], 401);
            }

            if (Carbon::createFromFormat('Y/m/d H:i:s', $datetime)?->addSeconds(30)->isPast()) {
                return response()->json(['error' => 'Token expired'], 401);
            }
        } catch (\Throwable $e) {
            Log::error('Token verify failed: ' . $e->getMessage());
            return response()->json(['error' => 'Token verification failed'], 401);
        }

        return $next($request);
    }

    private function decryptData(string $encrypted, string $key): ?string
    {
        try {
            return (new Encryption())->decrypt($encrypted, $key);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
