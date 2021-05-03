<?php
namespace App\Security;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Encrypted extends Type
{

    /**
     * @var string only if cipher isn´t set/found in env
     */
    private $defaultCipher = 'aes-256-gcm';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName(): string
    {
        return 'encrypted';
    }

    /**
     * Encrypts the data for a somewhat secure saving in database
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value === '') {
            return '';
        }

        // ENCRYPTION

        $cipher = empty($_ENV['APP_SECRET_CIPHER']) === false ? $_ENV['APP_SECRET_CIPHER'] : $this->defaultCipher;
        $passphrase = $_ENV['APP_SECRET'];

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($value, $cipher, $passphrase, 0, $iv, $tag);

        return base64_encode($iv) .'.'. base64_encode($tag) .'.'. base64_encode($encrypted);
    }

    /**
     * Decrypts the data from database
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        if ($value === '') {
            return '';
        }
        // DECRYPTION

        $value = explode('.', $value);
        if (count($value) != 3) {
            return '';
        }

        $cipher = empty($_ENV['APP_SECRET_CIPHER']) === false ? $_ENV['APP_SECRET_CIPHER'] : $this->defaultCipher;
        $passphrase = $_ENV['APP_SECRET'];

        $iv = base64_decode($value[0]);
        $tag = base64_decode($value[1]);
        $value = base64_decode($value[2]);

        return openssl_decrypt($value, $cipher, $passphrase, 0, $iv, $tag);
    }

}