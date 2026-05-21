<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\ShipmentTypesRestApi\Api\Storefront\Exception;

use Spryker\ApiPlatform\Exception\GlueApiException;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShipmentTypesRestApiExceptionFactory
{
    protected const string RESPONSE_DETAIL_SHIPMENT_TYPE_ENTITY_NOT_FOUND = 'A delivery type entity was not found.';

    public function createShipmentTypeNotFoundException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_NOT_FOUND,
            ShipmentTypesRestApiConfig::RESPONSE_CODE_SHIPMENT_TYPE_ENTITY_NOT_FOUND,
            static::RESPONSE_DETAIL_SHIPMENT_TYPE_ENTITY_NOT_FOUND,
        );
    }
}
