<?php
/**
 * MageHelper Approve Customer Account Magento 2
 *
 * @package      MageHelper_ApproveCustomerAccount
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\ApproveCustomerAccount\Plugin;

use Magento\Framework\Exception\LocalizedException;

class ApproveCustomerAccount
{
    protected $_mageHelper;
    
    public function __construct(
        \MageHelper\ApproveCustomerAccount\Helper\MageHelper $mageHelper
    ) {
        $this->_mageHelper = $mageHelper;
    }

    public function afterAuthenticate(
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        $result
    ) {
        if($this->_mageHelper->getConfig('magehelper/approve_account/enable')){
            if($result->getCustomAttribute('magehelper_approve_account')){
                $customerStatus = $result->getCustomAttribute('magehelper_approve_account')->getValue();
                if ($customerStatus == 0) {
                    throw new LocalizedException(__('Your Account still not approved. Please contact store owner for more details.'));
                }
            }
        }
        return $result;
    }
}