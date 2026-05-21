<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\ShipmentTypesRestApi\Api\Storefront\Provider;

use Generated\Api\Storefront\ShipmentTypesStorefrontResource;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Glue\ShipmentTypesRestApi\Api\Storefront\Exception\ShipmentTypesRestApiExceptionFactory;

class ShipmentTypesStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string URI_VAR_UUID = 'uuid';

    protected const string SORT_FIELD_KEY = 'key';

    protected const string SORT_DIRECTION_DESC = 'DESC';

    public function __construct(
        protected ShipmentTypeStorageClientInterface $shipmentTypeStorageClient,
        protected ShipmentTypesRestApiExceptionFactory $exceptionFactory = new ShipmentTypesRestApiExceptionFactory(),
    ) {
    }

    protected function provideItem(): object|null
    {
        $uuid = (string)$this->getUriVariable(static::URI_VAR_UUID);

        $conditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setStoreName($this->getStore()->getNameOrFail())
            ->addUuid($uuid);

        $criteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($conditionsTransfer);

        $collectionTransfer = $this->shipmentTypeStorageClient->getShipmentTypeStorageCollection($criteriaTransfer);

        if ($collectionTransfer->getShipmentTypeStorages()->count() === 0) {
            throw $this->exceptionFactory->createShipmentTypeNotFoundException();
        }

        return $this->mapTransferToResource($collectionTransfer->getShipmentTypeStorages()->offsetGet(0));
    }

    /**
     * @return array<\Generated\Api\Storefront\ShipmentTypesStorefrontResource>
     */
    protected function provideCollection(): array
    {
        $conditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setStoreName($this->getStore()->getNameOrFail());

        $criteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($conditionsTransfer);

        $collectionTransfer = $this->shipmentTypeStorageClient->getShipmentTypeStorageCollection($criteriaTransfer);

        $resources = $this->mapCollectionToResources($collectionTransfer);

        $sortParam = $this->getRequest()->query->get('sort');
        if ($sortParam !== null) {
            $resources = $this->sortResources($resources, (string)$sortParam);
        }

        return $resources;
    }

    /**
     * @return array<\Generated\Api\Storefront\ShipmentTypesStorefrontResource>
     */
    protected function mapCollectionToResources(ShipmentTypeStorageCollectionTransfer $collectionTransfer): array
    {
        $resources = [];

        foreach ($collectionTransfer->getShipmentTypeStorages() as $storageTransfer) {
            $resources[] = $this->mapTransferToResource($storageTransfer);
        }

        return $resources;
    }

    protected function mapTransferToResource(ShipmentTypeStorageTransfer $storageTransfer): ShipmentTypesStorefrontResource
    {
        $resource = new ShipmentTypesStorefrontResource();
        $resource->uuid = $storageTransfer->getUuid();
        $resource->name = $storageTransfer->getName();
        $resource->key = $storageTransfer->getKey();
        $resource->serviceTypeUuid = $storageTransfer->getServiceType()?->getUuid();

        return $resource;
    }

    /**
     * @param array<\Generated\Api\Storefront\ShipmentTypesStorefrontResource> $resources
     *
     * @return array<\Generated\Api\Storefront\ShipmentTypesStorefrontResource>
     */
    protected function sortResources(array $resources, string $sortParam): array
    {
        $sortDirection = static::SORT_DIRECTION_DESC;
        $field = $sortParam;

        if (!str_starts_with($sortParam, '-')) {
            $sortDirection = 'ASC';
        } else {
            $field = substr($sortParam, 1);
        }

        if ($field !== static::SORT_FIELD_KEY) {
            return $resources;
        }

        usort($resources, static function (ShipmentTypesStorefrontResource $a, ShipmentTypesStorefrontResource $b) use ($sortDirection): int {
            $comparison = strcmp($a->key ?? '', $b->key ?? '');

            return $sortDirection === static::SORT_DIRECTION_DESC ? -$comparison : $comparison;
        });

        return $resources;
    }
}
