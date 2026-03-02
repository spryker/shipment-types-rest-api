<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\StrategyResolver;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Spryker\Zed\ShipmentTypesRestApi\Business\Validator\ShipmentTypeCheckoutDataValidatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface ShipmentTypeCheckoutDataValidatorStrategyResolverInterface
{
    public function resolve(CheckoutDataTransfer $checkoutDataTransfer): ShipmentTypeCheckoutDataValidatorInterface;
}
