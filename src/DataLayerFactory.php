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

namespace MagmaDataLayer;

use MagmaDataLayer\DatabaseConnection\DatabaseConnection;
use MagmaDataLayer\DataMapper\DataMapperFactory;
use MagmaDataLayer\EntityManager\EntityManagerFactory;
use MagmaDataLayer\QueryBuilder\QueryBuilderFactory;
use MagmaDataLayer\QueryBuilder\QueryBuilder;
use MagmaDataLayer\EntityManager\Crud;
use MagmaDataLayer\DataLayerEnvironment;

class DataLayerFactory
{
    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var DataLayerEnvironment */
    protected DataLayerEnvironment $environment;

    /**
     * Main class constructor
     *
     * @param DataLayerEnvironment $environment
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param array|null $options
     */
    public function __construct(DataLayerEnvironment $environment, string $tableSchema, string $tableSchemaID)
    {
        $this->environment = $environment;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * build method which glues all the components together and inject the necessary 
     * dependency within the respective object
     *
     * @return Object
     */
    public function build() : Object
    {
        /* build the data mapper factory object */
        $dataMapperFactory = new DataMapperFactory();
        $dataMapper = $dataMapperFactory->create(DatabaseConnection::class, $this->environment);
        if ($dataMapper) {
            /* build the query builder factory object */
            $queryBuilderFactory = new QueryBuilderFactory();
            $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);
            if ($queryBuilder) {
                /* build the entity manager factory object */
                $entityManagerFactory = new EntityManagerFactory($dataMapper, $queryBuilder);
                return $entityManagerFactory->create(Crud::class, $this->tableSchema, $this->tableSchemaID);
            }
        }
    }

}
