<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreatorInterface;
use Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReaderInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class SingleShipmentShipmentTypeCheckoutDataValidator extends AbstractShipmentTypeCheckoutDataValidator implements ShipmentTypeCheckoutDataValidatorInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReaderInterface
     */
    protected ShipmentMethodReaderInterface $shipmentMethodReader;

    /**
     * @var \Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreatorInterface
     */
    protected ShipmentTypeCheckoutErrorCreatorInterface $shipmentTypeCheckoutErrorCreator;

    public function __construct(
        ShipmentMethodReaderInterface $shipmentMethodReader,
        ShipmentTypeCheckoutErrorCreatorInterface $shipmentTypeCheckoutErrorCreator
    ) {
        $this->shipmentMethodReader = $shipmentMethodReader;
        $this->shipmentTypeCheckoutErrorCreator = $shipmentTypeCheckoutErrorCreator;
    }

    public function validateShipmentTypeCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        if (!$checkoutDataTransfer->getShipment() || !$checkoutDataTransfer->getShipment()->getIdShipmentMethod()) {
            return $checkoutResponseTransfer;
        }

        $idShipmentMethod = $checkoutDataTransfer->getShipmentOrFail()->getIdShipmentMethodOrFail();
        $shipmentMethodTransfersIndexedByIdShipmentMethod = $this->shipmentMethodReader->getShipmentMethodTransfersIndexedByIdShipmentMethod();
        $shipmentMethodTransfer = $shipmentMethodTransfersIndexedByIdShipmentMethod[$idShipmentMethod] ?? null;
        if (!$shipmentMethodTransfer || !$shipmentMethodTransfer->getShipmentType()) {
            return $checkoutResponseTransfer;
        }

        $storeTransfer = $checkoutDataTransfer->getQuoteOrFail()->getStoreOrFail();
        if (!$this->isValidShipmentType($shipmentMethodTransfer->getShipmentTypeOrFail(), $storeTransfer)) {
            $checkoutErrorTransfer = $this
                ->shipmentTypeCheckoutErrorCreator
                ->createShipmentTypeNotAvailableCheckoutErrorTransfer($shipmentMethodTransfer->getShipmentTypeOrFail());

            return $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($checkoutErrorTransfer);
        }

        return $checkoutResponseTransfer;
    }
}
