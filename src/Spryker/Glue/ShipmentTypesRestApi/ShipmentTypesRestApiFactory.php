<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi;

use Spryker\Glue\Kernel\AbstractStorefrontApiFactory;
use Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToShipmentTypeStorageClientInterface;
use Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToStoreClientInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ErrorResponseBuilder;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ErrorResponseBuilderInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ShipmentTypeResponseBuilder;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ShipmentTypeResponseBuilderInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Expander\CheckoutDataResponseAttributesExpander;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Expander\CheckoutDataResponseAttributesExpanderInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Expander\ShipmentTypeByShipmentMethodResourceRelationshipExpander;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Expander\ShipmentTypeByShipmentMethodResourceRelationshipExpanderInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper\ShipmentTypeMapper;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Reader\ShipmentTypeReader;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Reader\ShipmentTypeReaderInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Sorter\ShipmentTypeSorter;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Sorter\ShipmentTypeSorterInterface;

/**
 * @method \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig getConfig()
 */
class ShipmentTypesRestApiFactory extends AbstractStorefrontApiFactory
{
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeStorageClient(),
            $this->getStoreClient(),
            $this->createShipmentTypeResponseBuilder(),
            $this->createShipmentTypeSorter(),
        );
    }

    public function createShipmentTypeResponseBuilder(): ShipmentTypeResponseBuilderInterface
    {
        return new ShipmentTypeResponseBuilder(
            $this->getResourceBuilder(),
            $this->createShipmentTypeMapper(),
            $this->createErrorResponseBuilder(),
        );
    }

    public function createShipmentTypeMapper(): ShipmentTypeMapperInterface
    {
        return new ShipmentTypeMapper();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\ShipmentTypesRestApi\Processor\Expander\CheckoutDataResponseAttributesExpanderInterface
     */
    public function createCheckoutDataResponseAttributesExpander(): CheckoutDataResponseAttributesExpanderInterface
    {
        return new CheckoutDataResponseAttributesExpander();
    }

    public function createShipmentTypeSorter(): ShipmentTypeSorterInterface
    {
        return new ShipmentTypeSorter();
    }

    public function createErrorResponseBuilder(): ErrorResponseBuilderInterface
    {
        return new ErrorResponseBuilder(
            $this->getConfig(),
            $this->getResourceBuilder(),
            $this->getGlossaryStorageClient(),
        );
    }

    public function createShipmentTypeByShipmentMethodResourceRelationshipExpander(): ShipmentTypeByShipmentMethodResourceRelationshipExpanderInterface
    {
        return new ShipmentTypeByShipmentMethodResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createShipmentTypeMapper(),
        );
    }

    public function getShipmentTypeStorageClient(): ShipmentTypesRestApiToShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypesRestApiDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }

    public function getStoreClient(): ShipmentTypesRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypesRestApiDependencyProvider::CLIENT_STORE);
    }

    public function getGlossaryStorageClient(): ShipmentTypesRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypesRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
