<?php

namespace FoamyCastle\Log\MySQL;

use FoamyCastle\Log\File\FileTarget;
use PDO;
use PDOException;

class MySQLTarget extends \FoamyCastle\Log\LogTarget
{
    private const DATABASE_NAME = "Forester";
    private const DATABASE_USERNAME="root";
    private const DATABASE_PASSWORD="j\$yPs6c4kMKstngn";
    private const DATABASE_HOST='localhost';
    private const DATABASE_PORT=3306;
    private string $database;
    private string $schema;
    private PDO $pdo;
    public function __construct(string $schema)
    {
        $this->database=self::DATABASE_NAME;
        $this->schema=$schema;
        $this->pdo=$this->pdoConnect();
    }
    private function pdoConnect():PDO
    {
        try {
            return new PDO(
                $this->prepareDNSString(),
                self::DATABASE_USERNAME,
                self::DATABASE_PASSWORD
            );
        }catch (PDOException $exception){

        }
    }
    private function prepareDNSString():string{
        $dns='mysql';
        $host=self::DATABASE_HOST;
        $port=self::DATABASE_PORT;
        $dbname=self::DATABASE_NAME;
        return "$dns:host=$host;port=$port;dbname=$dbname";
    }
    /**
     * @inheritDoc
     */
    protected function targetCreate(array|string $options): mixed
    {
        // TODO: Implement targetCreate() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetOpen(array|string $options): mixed
    {
        // TODO: Implement targetOpen() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetClose(array|string $options): bool
    {
        // TODO: Implement targetClose() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetExists(array|string $options): bool
    {
        // TODO: Implement targetExists() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetMakeReady(array|string $options): bool
    {
        // TODO: Implement targetMakeReady() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetUnset(array|string $options): bool
    {
        // TODO: Implement targetUnset() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetClear(array|string $options): bool
    {
        // TODO: Implement targetClear() method.
    }

    /**
     * @inheritDoc
     */
    function writeMessage(string $message): bool
    {
        // TODO: Implement writeMessage() method.
    }
}