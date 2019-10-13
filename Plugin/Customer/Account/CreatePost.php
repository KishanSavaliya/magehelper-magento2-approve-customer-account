<?php
/**
 * MageHelper Approve Customer Account Magento 2
 *
 * @package      MageHelper_ApproveCustomerAccount
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\ApproveCustomerAccount\Plugin\Customer\Account;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;

class CreatePost
{
    protected $url;

    protected $session;

    private $cookieMetadataFactory;

    private $cookieMetadataManager;

    protected $_mageHelper;

    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Customer\Model\Session $customerSession,
        \MageHelper\ApproveCustomerAccount\Helper\MageHelper $mageHelper
    ){
        $this->session = $customerSession;
        $this->url = $url;
        $this->_mageHelper = $mageHelper;
    }

    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = ObjectManager::getInstance()->get(PhpCookieManager::class);
        }
        return $this->cookieMetadataManager;
    }

    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = ObjectManager::getInstance()->get(CookieMetadataFactory::class);
        }
        return $this->cookieMetadataFactory;
    }

    public function afterExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        $resultRedirect
    ) {
        if($this->_mageHelper->getConfig('magehelper/approve_account/enable')){
            $lastCustomerId = $this->session->getId();
            $this->session->logout()->setLastCustomerId($lastCustomerId);
            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }
        }
        return $resultRedirect;
    }
}