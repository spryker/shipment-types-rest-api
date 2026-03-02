<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Builder;

use Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;

class ShipmentTypeResponseBuilder implements ShipmentTypeResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper\ShipmentTypeMapperInterface
     */
    protected ShipmentTypeMapperInterface $shipmentTypeMapper;

    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShipmentTypeMapperInterface $shipmentTypeMapper,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shipmentTypeMapper = $shipmentTypeMapper;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    public function createShipmentTypeRestResponse(ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer): RestResponseInterface
    {
        $restShipmentTypesAttributesTransfer = $this
            ->shipmentTypeMapper
            ->mapShipmentTypeStorageTransferToRestShipmentTypesAttributesTransfer(
                $shipmentTypeStorageTransfer,
                new RestShipmentTypesAttributesTransfer(),
            );
        $shipmentTypeRestResource = $this->createShipmentTypeRestResource(
            $restShipmentTypesAttributesTransfer,
            $shipmentTypeStorageTransfer->getUuidOrFail(),
        );
        $shipmentTypeRestResource->setPayload($shipmentTypeStorageTransfer);

        return $this->restResourceBuilder->createRestResponse()->addResource($shipmentTypeRestResource);
    }

    public function createShipmentTypeCollectionRestResponse(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            $restShipmentTypesAttributesTransfer = $this
                ->shipmentTypeMapper
                ->mapShipmentTypeStorageTransferToRestShipmentTypesAttributesTransfer(
                    $shipmentTypeStorageTransfer,
                    new RestShipmentTypesAttributesTransfer(),
                );
            $shipmentTypeRestResource = $this->createShipmentTypeRestResource(
                $restShipmentTypesAttributesTransfer,
                $shipmentTypeStorageTransfer->getUuidOrFail(),
            );
            $restResponse->addResource($shipmentTypeRestResource);
            $shipmentTypeRestResource->setPayload($shipmentTypeStorageTransfer);
        }

        return $restResponse;
    }

    public function createShipmentTypeNotFoundErrorResponse(string $locale): RestResponseInterface
    {
        return $this
            ->errorResponseBuilder
            ->createErrorResponse(
                ShipmentTypesRestApiConfig::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND,
                $locale,
            );
    }

    protected function createShipmentTypeRestResource(
        RestShipmentTypesAttributesTransfer $restShipmentTypesAttributesTransfer,
        string $shipmentTypeUuid
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
            $shipmentTypeUuid,
            $restShipmentTypesAttributesTransfer,
        );
    }
}
