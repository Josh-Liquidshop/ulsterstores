<?php


class Moogento_Core_Model_Sales_Order extends Mage_Sales_Model_Order
{
    protected $_mdnOrder = null;

    protected function _construct()
    {
        parent::_construct();
        if (Mage::helper('moogento_core')->isInstalled('MDN_AdvancedStock') && mageFindClassFile('MDN_AdvancedStock_Model_Sales_Order')) {
            $this->_mdnOrder = Mage::getModel('MDN_AdvancedStock_Model_Sales_Order');
        }
    }


    /**
     * Retrieve order shipment availability
     *
     * @return bool
     */
    public function canShip()
    {
        if ($this->canUnhold() || $this->isPaymentReview()) {
            return false;
        }

        if ($this->getIsVirtual() || $this->isCanceled()) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_SHIP) === false) {
            return false;
        }

        foreach ($this->getAllItems() as $item) {
            if ($item->getQtyToShip()>0 && !$item->getIsVirtual()
                && !$item->getLockedDoShip())
            {
                return true;
            }
        }
        return false;
    }

    protected function ignoreCanShip()
    {
        return Mage::getStoreConfigFlag('courierrules/settings/ignore_can_ship') ? true : false;
    }

    protected function _checkState()
    {
        if (Mage::getStoreConfig('moogento_statuses/settings/status_processing') == Moogento_Core_Model_System_Config_Source_Status_Processing::CUSTOM && Mage::registry('ignore_status_check')) {
            //return $this;
        }

        if (!$this->getId()) {
            return $this;
        }
        $userNotification = $this->hasCustomerNoteNotify() ? $this->getCustomerNoteNotify() : null;

        if (!$this->isCanceled()
            && !$this->canUnhold()
            && !$this->canInvoice()
            && (!$this->canShip())) {
            if (0 == $this->getBaseGrandTotal() || $this->canCreditmemo()) {
                if(!$this->canShip() && $this->ignoreCanShip()) {
                    if ($this->getState() !== self::STATE_PROCESSING) {
                        $this->_setState(self::STATE_PROCESSING, true, '', $userNotification);
                    }
                }
                else {
                    if ($this->getState() !== self::STATE_COMPLETE) {
                        $this->_setState(self::STATE_COMPLETE, true, '', $userNotification);
                    }
                }
            }
            /**
             * Order can be closed just in case when we have refunded amount.
             * In case of "0" grand total order checking ForcedCanCreditmemo flag
             */
            elseif (floatval($this->getTotalRefunded()) || (!$this->getTotalRefunded()
                    && $this->hasForcedCanCreditmemo())
            ) {
                if ($this->getState() !== self::STATE_CLOSED) {
                    $this->_setState(self::STATE_CLOSED, true, '', $userNotification);
                }
            }
        }

        if ($this->getState() == self::STATE_NEW && $this->getIsInProcess()) {
            $this->setState(self::STATE_PROCESSING, true, '', $userNotification);
        }
        return $this;
    }


    public function isStateProtected($state)
    {
        if (Mage::getStoreConfig('moogento_statuses/settings/status_processing') == Moogento_Core_Model_System_Config_Source_Status_Processing::CUSTOM && Mage::registry('ignore_status_check')) {
            return false;
        }

        return parent::isStateProtected($state);
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();
        if (!is_null($this->_mdnOrder)) {
            $this->_mdnOrder->setData($this->getData());
        }
    }

    public function __call($method, $args)
    {
        if (!is_null($this->_mdnOrder) && method_exists($this->_mdnOrder, $method)) {
            return call_user_func_array(array($this->_mdnOrder, $method), $args);
        }

        return parent::__call($method, $args);
    }

    public function getShippingMethod($asObject = false)
    {
        $shippingMethod = parent::getShippingMethod();
        if ($asObject) {
            list($carrierCode, $method) = explode('_', $shippingMethod, 2);
            $shippingMethod = new Varien_Object(array(
                'carrier_code' => $carrierCode,
                'method'       => $method
            ));
        }
        $data = new Varien_Object(array(
            'method' =>$shippingMethod,
            'as_object' => $asObject,
        ));

        Mage::dispatchEvent('moogento_core_order_get_shipping_method',
            array('order' => $this, 'values' => $data));
        return $data->getMethod();
    }

    public function getShippingDescription()
    {
        $shippingDescription = parent::getShippingDescription();
        $data = new Varien_Object(array(
            'description' =>$shippingDescription,
        ));

        Mage::dispatchEvent('moogento_core_order_get_shipping_description',
            array('order' => $this, 'values' => $data));
        return $data->getDescription();
    }
}
