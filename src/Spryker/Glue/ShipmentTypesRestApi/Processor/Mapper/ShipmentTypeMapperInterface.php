<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface ShipmentTypeMapperInterface
{
    public function mapShipmentTypeStorageTransferToRestShipmentTypesAttributesTransfer(
        ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer,
        RestShipmentTypesAttributesTransfer $restShipmentTypesAttributesTransfer
    ): RestShipmentTypesAttributesTransfer;

    public function mapShipmentTypeTransferToRestShipmentTypesAttributesTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        RestShipmentTypesAttributesTransfer $restShipmentTypesAttributesTransfer
    ): RestShipmentTypesAttributesTransfer;
}
