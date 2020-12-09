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

namespace MagmaDataLayer\ClientRepository;

use MagmaDataLayer\Exception\DataLayerUnexpectedValueException;
use MagmaDataLayer\ClientRepository\ClientRepositoryInterface;
use MagmaDataLayer\DataLayerEnvironment;
use MagmaDataLayer\DataLayerConfiguration;
use MagmaDataLayer\DataLayerFactory;

class ClientRepositoryFactory
{

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var string */
    protected string $crudIdentifier;

    /**
     * Main class constructor
     *
     * @param string $crudIdentifier
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * The client repository is a internal data layer which expose methods for internal
     * and external library data consumption. It also provides method for puting and droping
     * data from the client specified data entities.
     *
     * @param string $ClientRepositoryString
     * @param array|null $dataLayerConfiguration
     * @return ClientRepositoryInterface
     * @throws DataLayerUnexpectedValueException
     */
    public function create(string $ClientRepositoryString, ?array $dataLayerConfiguration = null) : ClientRepositoryInterface
    {
        
        $clientRepositoryObject = new $ClientRepositoryString($this->entityBuilder($dataLayerConfiguration));
        if (!$clientRepositoryObject instanceof ClientRepositoryInterface) {
            throw new DataLayerUnexpectedValueException($ClientRepositoryString . ' is not a valid repository object');
        }
        return $clientRepositoryObject;
    }

    /**
     * Undocumented function
     *
     * @param array|null $dataLayerConfiguration
     * @return Object
     */
    public function entityBuilder(?array $dataLayerConfiguration = null) : Object
    {
        $dataLayerEnvironment = new DataLayerEnvironment(new DataLayerConfiguration(($dataLayerConfiguration !=null) ? $dataLayerConfiguration : (new DataLayerConfiguration())->baseConfiguration()));
        
        $factory = new DataLayerFactory($dataLayerEnvironment, $this->tableSchema, $this->tableSchemaID);
        if ($factory) {
            return $factory->build();
        }

    }


}
