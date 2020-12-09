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

namespace MagmaDataLayer\DataMapper;

use MagmaDataLayer\Exception\DataLayerUnexpectedValueException;
use MagmaDataLayer\DatabaseConnection\DatabaseConnectionInterface;
use MagmaDataLayer\DataLayerConfiguration;

class DataMapperFactory
{

    /**
     * Main constructor class
     * 
     * @return void
     */
    public function __construct()
    { }

    /**
     * Creates the data mapper object and inject the dependency for this object. We are also
     * creating the DatabaseConnection Object
     * $dataMapperEnvironmentConfiguration get instantiated in the DataRepositoryFactory
     *
     * @param string $databaseConnectionString
     * @param Object $dataMapperEnvironmentConfiguration
     * @return DataMapperInterface
     * @throws BaseUnexpectedValueException
     */
    public function create(string $databaseConnectionString, Object $environment) : DataMapperInterface
    {
        // Create databaseConnection Object and pass the database credentials in
        $defaultDriver = (new DataLayerConfiguration())['default']; /* Get the database default driver */
        $credentials = $environment->getDatabaseCredentials($defaultDriver);
        $databaseConnectionObject = new $databaseConnectionString($credentials);
        
        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
            throw new DataLayerUnexpectedValueException($databaseConnectionString . ' is not a valid database connection object');
        }
        return new DataMapper($databaseConnectionObject);
    }


}
