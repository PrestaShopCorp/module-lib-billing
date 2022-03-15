<?php

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

namespace PrestaShopCorp\Billing\Presenter;

use Module;
use PrestaShopCorp\Billing\Builder\EnvBuilder;
use PrestaShopCorp\Billing\Builder\UrlBuilder;
use PrestaShopCorp\Billing\Wrappers\BillingAccountsWrapper;

class BillingPresenter
{
    /**
     * @var EnvBuilder
     */
    private $envBuilder;

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * @var BillingAccountsWrapper
     */
    private $billingAccountsWrapper;

    /**
     * @var \Module
     */
    private $module;

    /**
     * Presenter constructor.
     *
     * @param \Module $module
     * @param PsAccounts $accountFacade
     * @param \Context|null $context
     */
    public function __construct(
        BillingAccountsWrapper $billingAccountsWrapper = null,
        Module $module
    ) {
        $this->setModule($module);

        $this->setEnvBuilder(new EnvBuilder());
        $this->setUrlBuilder(new UrlBuilder());

        $this->setBillingAccountsWrapper($billingAccountsWrapper);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function present(array $params)
    {
        $getEnv = $this->getBillingAccountsWrapper()->getBillingEnv()
            ? $this->getBillingAccountsWrapper()->getBillingEnv()
            : '';
        $billingEnv = $this->getEnvBuilder()->buildBillingEnv($getEnv);

        return [
            'psBillingContext' => [
                'context' => [
                    'billingEnv' => $billingEnv,
                    'billingUIUrl' => $this->getUrlBuilder()->buildUIUrl($billingEnv),
                    'isSandbox' => $this->getBillingAccountsWrapper()->isSandbox()
                        ? $this->getBillingAccountsWrapper()->isSandbox()
                        : false,

                    'versionPs' => _PS_VERSION_,
                    'versionModule' => $this->getModule()->version,
                    'moduleName' => $this->getModule()->name,
                    'displayName' => $this->getModule()->displayName,

                    'i18n' => [
                        'isoCode' => $this->getBillingAccountsWrapper()->getLanguageIsoCode(),
                    ],

                    'refreshToken' => $this->getBillingAccountsWrapper()->getRefreshToken(),
                    'shop' => [
                        'uuid' => $this->getBillingAccountsWrapper()->getShopUuid(),
                    ],
                    'user' => [
                        'createdFromIp' => \Tools::getRemoteAddr(),
                        'email' => $this->getBillingAccountsWrapper()->getEmail(),
                    ],

                    'moduleLogo' => $this->encodeImage($this->getModuleLogo()),
                    'partnerLogo' => !empty($params['logo']) ? $this->encodeImage($params['logo']) : '',
                    // TODO: Use \Validate::isUrl($params['tosLink']) throws error
                    'moduleTosUrl' => !empty($params['tosLink']) ? $params['tosLink'] : '',
                    // TODO: Use \Validate::isEmail($params['emailSupport']) throws error
                    'emailSupport' => !empty($params['emailSupport']) ? $params['emailSupport'] : '',
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    private function encodeImage(string $image_path)
    {
        $mime_type = $this->getMimeTypeByExtension($image_path);
        if ($mime_type === null) {
            return $mime_type;
        }

        $image_content = \Tools::file_get_contents($image_path);

        return 'data:' . $mime_type . ';base64,' . base64_encode($image_content);
    }

    /**
     * Return the mime type by the file extension.
     *
     * @param string $fileName
     *
     * @return string
     */
    private function getMimeTypeByExtension($fileName)
    {
        $types = [
            'image/gif' => ['gif'],
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/webp' => ['webp'],
            'image/svg+xml' => ['svg'],
        ];
        $extension = substr($fileName, strrpos($fileName, '.') + 1);

        $mimeType = null;
        foreach ($types as $mime => $exts) {
            if (in_array($extension, $exts)) {
                $mimeType = $mime;
                break;
            }
        }

        return $mimeType;
    }

    /**
     * @return string
     */
    private function getModuleLogo()
    {
        if (@filemtime($this->getModule()->getLocalPath() . 'logo.png')) {
            return $this->getModule()->getLocalPath() . 'logo.png';
        }

        return $this->getModule()->getLocalPath() . 'logo.gif';
    }

    /**
     * setEnvBuilder
     *
     * @param EnvBuilder $envBuilder
     *
     * @return void
     */
    private function setEnvBuilder($envBuilder)
    {
        $this->envBuilder = $envBuilder;
    }

    /**
     * getEnvBuilder
     *
     * @return EnvBuilder
     */
    private function getEnvBuilder()
    {
        return $this->envBuilder;
    }

    /**
     * setUrlBuilder
     *
     * @param UrlBuilder $urlBuilder
     *
     * @return void
     */
    private function setUrlBuilder($urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * getUrlBuilder
     *
     * @return UrlBuilder
     */
    private function getUrlBuilder()
    {
        return $this->urlBuilder;
    }

    /**
     * setBillingAccountsWrapper
     *
     * @param BillingAccountsWrapper $billingAccountsWrapper
     *
     * @return void
     */
    private function setBillingAccountsWrapper($billingAccountsWrapper)
    {
        $this->billingAccountsWrapper = $billingAccountsWrapper;
    }

    /**
     * getBillingAccountsWrapper
     *
     * @return BillingAccountsWrapper
     */
    private function getBillingAccountsWrapper()
    {
        return $this->billingAccountsWrapper;
    }

    /**
     * setModule
     *
     * @param \Module $module
     *
     * @return void
     */
    private function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * getModule
     *
     * @return \Module
     */
    private function getModule()
    {
        return $this->module;
    }
}
