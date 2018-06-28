<?php

namespace MageDawg\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_SOCIAL_LINKS           = 'sociallinks/';
    const XML_PATH_SOCIAL_LINKS_ACCOUNTS  = self::XML_PATH_SOCIAL_LINKS . 'accounts/';

    // Social Accounts
    const XML_PATH_SOCIAL_LINK_FLICKR     = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'flickr';
    const XML_PATH_SOCIAL_LINK_GITHUB     = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'github';
    const XML_PATH_SOCIAL_LINK_REDDIT     = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'reddit';
    const XML_PATH_SOCIAL_LINK_TWITTER    = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'twitter';
    const XML_PATH_SOCIAL_LINK_YOUTUBE    = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'youtube';
    const XML_PATH_SOCIAL_LINK_FACEBOOK   = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'facebook';
    const XML_PATH_SOCIAL_LINK_LINKEDIN   = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'linkedin';
    const XML_PATH_SOCIAL_LINK_PINTEREST  = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'pinterest';
    const XML_PATH_SOCIAL_LINK_INSTAGRAM  = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'instagram';
    const XML_PATH_SOCIAL_LINK_GOOGLEPLUS = self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . 'googleplus';

    const SOCIAL_ACCOUNTS                 = array(
        'flickr'      => self::XML_PATH_SOCIAL_LINK_FLICKR,
        'github'      => self::XML_PATH_SOCIAL_LINK_GITHUB,
        'reddit'      => self::XML_PATH_SOCIAL_LINK_REDDIT,
        'twitter'     => self::XML_PATH_SOCIAL_LINK_TWITTER,
        'youtube'     => self::XML_PATH_SOCIAL_LINK_YOUTUBE,
        'facebook'    => self::XML_PATH_SOCIAL_LINK_FACEBOOK,
        'linkedin'    => self::XML_PATH_SOCIAL_LINK_LINKEDIN,
        'pinterest'   => self::XML_PATH_SOCIAL_LINK_PINTEREST,
        'instagram'   => self::XML_PATH_SOCIAL_LINK_INSTAGRAM,
        'google-plus' => self::XML_PATH_SOCIAL_LINK_GOOGLEPLUS,
    );

    /** @var array $socialAccountLinks */
    protected $socialAccountLinks = array();

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * @param $account
     * @param null $storeId
     * @return mixed
     */
    public function getAccountLink($account, $storeId = null)
    {
        if (!isset($this->socialAccountLinks[$account])) {
            $this->socialAccountLinks[$account] = $this->getConfigValue(
                self::XML_PATH_SOCIAL_LINKS_ACCOUNTS . $account,
                $storeId
            );
        }

        return $this->socialAccountLinks[$account];
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getAccountLinks($storeId = null)
    {
        foreach (self::SOCIAL_ACCOUNTS as $account => $path) {
            if (!isset($this->socialAccountLinks[$account])) {
                $this->socialAccountLinks[$account] = $this->getConfigValue($path, $storeId);
            }
        }

        return $this->socialAccountLinks;
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __call($name, $arguments)
    {
        switch (substr($name, 0, 3)) {
            case 'get' :
                if (substr($name, -4) == 'Link') {
                    $key = strtolower(substr($name, 3, strlen($name)-4));
                    $data = $this->getAccountLink($key, isset($args[0]) ? $arguments[0] : null);
                    return $data;
                }
                break;
            case 'has' :
                if (substr($name, -4) == 'Link') {
                    $key = strtolower(substr($name, 3, strlen($name) - 4));
                    $data = $this->getAccountLink($key, isset($args[0]) ? $arguments[0] : null);
                    return isset($data);
                }
                break;
        }

        throw new \Magento\Framework\Exception\LocalizedException(
            new \Magento\Framework\Phrase('Invalid method %1::%2', [get_class($this), $name])
        );
    }
}