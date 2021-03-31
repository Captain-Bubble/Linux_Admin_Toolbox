<?php
namespace App\Security\Encrypted;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Encrypted extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName(): string
    {
        return 'encrypted';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value === '') {
            return '';
        }

        // ENCRYPTION

        $crypter = $this->getCrypter($platform);

        return $crypter->encrypt($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        if ($value === '') {
            return '';
        }

        // DECRYPTION

        $crypter = $this->getCrypter($platform);

        return $crypter->decrypt($value);
    }

}