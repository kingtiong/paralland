<?php

namespace App\Support;

use Elliptic\EC;
use InvalidArgumentException;
use Kornrunner\Keccak;

final class EthereumPersonalSign
{
    public static function verify(string $message, string $signature, string $expectedAddress): bool
    {
        return strtolower(self::recoverAddress($message, $signature)) === strtolower($expectedAddress);
    }

    /**
     * Recover the signer address from a "personal_sign" signature.
     *
     * @return string 0x-prefixed lowercase address
     */
    public static function recoverAddress(string $message, string $signature): string
    {
        $sig = self::normalizeHex($signature);

        if (strlen($sig) !== 130) {
            throw new InvalidArgumentException('Signature must be 65 bytes (130 hex chars).');
        }

        $r = substr($sig, 0, 64);
        $s = substr($sig, 64, 64);
        $v = hexdec(substr($sig, 128, 2));
        if ($v >= 27) {
            $v -= 27;
        }
        if ($v !== 0 && $v !== 1) {
            throw new InvalidArgumentException('Invalid signature recovery id (v).');
        }

        $hash = self::personalSignHash($message);

        $ec = new EC('secp256k1');
        $pubKey = $ec->recoverPubKey($hash, ['r' => $r, 's' => $s], $v);

        // uncompressed public key: 04 + x(32) + y(32)
        $pubKeyHex = $pubKey->encode('hex');
        $pubKeyHex = self::normalizeHex($pubKeyHex);

        if (str_starts_with($pubKeyHex, '04')) {
            $pubKeyHex = substr($pubKeyHex, 2);
        }

        $address = substr(Keccak::hash(hex2bin($pubKeyHex), 256), -40);

        return '0x'.strtolower($address);
    }

    /**
     * @return string 32-byte hash as hex (no 0x prefix)
     */
    private static function personalSignHash(string $message): string
    {
        $prefix = "\x19Ethereum Signed Message:\n".strlen($message);
        $prefixed = $prefix.$message;

        return Keccak::hash($prefixed, 256);
    }

    private static function normalizeHex(string $hex): string
    {
        $hex = strtolower(trim($hex));
        if (str_starts_with($hex, '0x')) {
            $hex = substr($hex, 2);
        }

        if ($hex === '' || preg_match('/[^0-9a-f]/', $hex)) {
            throw new InvalidArgumentException('Invalid hex string.');
        }

        return $hex;
    }
}

