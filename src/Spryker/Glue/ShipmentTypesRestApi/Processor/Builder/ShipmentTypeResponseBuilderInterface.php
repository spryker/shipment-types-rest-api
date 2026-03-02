<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Builder;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ShipmentTypeResponseBuilderInterface
{
    public function createShipmentTypeRestResponse(ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer): RestResponseInterface;

    public function createShipmentTypeCollectionRestResponse(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
    ): RestResponseInterface;

    public function createShipmentTypeNotFoundErrorResponse(string $locale): RestResponseInterface;
}
