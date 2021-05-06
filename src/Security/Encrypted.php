<?php
namespace App\Security;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Encrypted extends Type
{

    /**
     * @var string only if cipher isn´t set/found in env
     */
    private string $defaultCipher = 'aes-256-gcm';

    private int $options = OPENSSL_RAW_DATA;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getClobTypeDeclarationSQL($column);
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
        $encrypted = openssl_encrypt($value, $cipher, $passphrase, $this->options, $iv, $tag);

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

        return openssl_decrypt($value, $cipher, $passphrase, $this->options, $iv, $tag);
    }

}