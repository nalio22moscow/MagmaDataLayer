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

use MagmaDataLayer\Exception\DataLayerInvalidArgumentException;
use MagmaDataLayer\DataLayerConfiguration;

class DataLayerEnvironment
{
    
    /** @var DataLayerConfiguration */
    protected DataLayerConfiguration $environmentConfiguration;

    /**
     * Main construct class
     * 
     * @param array $credentials
     * @return void
     */
    public function __construct(DataLayerConfiguration $environmentConfiguration)
    {
        $this->environmentConfiguration = $environmentConfiguration;
    }

    /**
     * Checks credentials for validity
     * 
     * @param string $driver
     * @return void
     */
    private function isCredentialsValid(string $driver) : void
    {
        if (empty($driver) || !is_array($this->credentials)) {
            throw new DataLayerInvalidArgumentException('Core Error: You have either not specify the default database driver or the database.yaml is returning null or empty.');
        }
    }

    /**
     * Get the user defined database connection array
     * 
     * @param string $driver
     * @return array
     * @throws LiquidInvalidArgumentException
     */
    public function getDatabaseCredentials(string $driver) : array
    {
        $connectionArray = [];
        $this->isCredentialsValid($driver);
        foreach ($this->credentials as $credential) {
            if (!array_key_exists($driver, $credential)) {
                throw new DataLayerInvalidArgumentException('Core Error: Your selected database driver is not supported. Please see the database.yaml file for all support driver. Or specify a supported driver from your app.yaml configuration file');
            } else {
                $connectionArray = $credential[$driver];
            }
        }
        return $connectionArray;
    }

}
