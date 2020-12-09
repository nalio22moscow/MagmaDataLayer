<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaDataLayer\DatabaseConnection;

use MagmaDataLayer\Exception\DataLayerException;
use PDOException;
use PDO;

class DatabaseConnection implements DatabaseConnectionInterface
{

    /** @var array */
    protected array $credentials;

    /** @var PDO */
    protected PDO $dbh;

    /** */
    public function __construct(array $credentials)
    {
        if ($this->credentials)
            $this->credentials = $credentials;
    }

    /**
     * 
     * @return PDO
     * @throws PDOException
     * @throws LiquidException
     */
    public function open() : PDO
    {
        try {
            $params = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $this->dbh = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params
            );
        } catch(PDOException $expection) {
            throw new DataLayerException($expection->getMessage(), (int)$expection->getCode());
        }

        return $this->dbh;
    }

    /** */
    public function close()
    {
        $this->dbh = null;
    }

}