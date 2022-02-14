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

namespace PrestaShop\PsBilling\Presenter;

use Module;
use PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts;
use PrestaShop\PsBilling\Builder\EnvBuilder;
use PrestaShop\PsBilling\Builder\UrlBuilder;
use PrestaShop\PsBilling\Config\Config;

class BillingPresenter
{
    /**
     * @var PsAccounts
     */
    private $psAccountsService;

    /**
     * @var \Module
     */
    private $module;

    /**
     * @var \Context
     */
    private $context;

    /**
     * @var EnvBuilder
     */
    private $envBuilder;

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * Presenter constructor.
     *
     * @param \Module $module
     * @param PsAccounts $accountFacade
     * @param \Context|null $context
     */
    public function __construct(
        PsAccounts $accountFacade = null,
        Module $module,
        \Context $context = null)
    {
        if (null === $context) {
            $context = \Context::getContext();
        }
        $this->context = $context;
        $this->module = $module;

        $this->envBuilder = new EnvBuilder();
        $this->urlBuilder = new UrlBuilder();

        $this->psAccountsService = ($accountFacade) ? $accountFacade->getPsAccountsService() : $this->getPsAccountsService();
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function present(array $params)
    {
        $getEnv = !empty($params['billingEnv']) ? $params['billingEnv'] : '';

        return [
            'psBillingContext' => [
                'context' => [
                    'billingEnv' => $this->envBuilder->buildBillingEnv($getEnv),
                    'billingUIUrl' => $this->urlBuilder->buildUIUrl($getEnv),
                    'isSandbox' => !empty($params['sandbox']) ? (bool) $params['sandbox'] : false,

                    'versionPs' => _PS_VERSION_,
                    'versionModule' => $this->module->version,
                    'moduleName' => $this->module->name,
                    'displayName' => $this->module->displayName,

                    'i18n' => [
                        'isoCode' => $this->getLanguageIsoCode(),
                    ],

                    'refreshToken' => $this->getRefreshToken(),
                    'shop' => [
                        'uuid' => $this->getShopUuid(),
                    ],
                    'user' => [
                        'createdFromIp' => \Tools::getRemoteAddr(),
                        'email' => $this->getEmail(),
                    ],

                    'moduleLogo' => $this->getModuleLogo(),
                    // TODO: Use \Validate::isUrl($params['logo']) throws error
                    'partnerLogo' => !empty($params['logo']) ? $params['logo'] : '',
                    // TODO: Use \Validate::isUrl($params['tosLink']) throws error
                    'moduleTosUrl' => !empty($params['tosLink']) ? $params['tosLink'] : '',
                    // TODO: Use \Validate::isEmail($params['emailSupport']) throws error
                    'emailSupport' => !empty($params['emailSupport']) ? $params['emailSupport'] : '',
                ],
            ],
        ];
    }

    /**
     * @return string|false
     */
    private function getShopUuid()
    {
        return method_exists($this->psAccountsService, 'getShopUuid') ?
            $this->psAccountsService->getShopUuid() :
            $this->psAccountsService->getShopUuidV4();
    }

    /**
     * Get the user firebase token.
     *
     * @return string|null
     */
    private function getRefreshToken()
    {
        return $this->psAccountsService->getRefreshToken();
    }

    /**
     * @return string|null
     */
    private function getEmail()
    {
        return $this->psAccountsService->getEmail();
    }

    /**
     * Return an instance of PS Account module.
     *
     * @return Module|false
     */
    private function getAccountInstance()
    {
        return \Module::getInstanceByName(Config::PS_ACCOUNTS_MODULE_NAME);
    }

    /**
     * @param string $serviceName
     *
     * @return mixed
     */
    private function getAccountService(string $serviceName)
    {
        return $this->getAccountInstance->getService($serviceName);
    }

    /**
     * @return mixed
     */
    private function getPsAccountsService()
    {
        return $this->getAccountService(Config::PS_ACCOUNTS_SERVICE);
    }

    /**
     * Get the isoCode from the context language, if null, send 'en' as default value
     *
     * @return string
     */
    private function getLanguageIsoCode()
    {
        return $this->context->language !== null ? $this->context->language->iso_code : Config::I18N_FALLBACK_LOCALE;
    }

    /**
     * @return string
     */
    private function getShopUri()
    {
        return \Tools::substr($this->context->link->getBaseLink(null, null, true), 0, -1);
    }

    /**
     * Get the isoCode from the context language, if null, send 'en' as default value
     *
     * @return string
     */
    private function getModuleLogo()
    {
        return $this->getShopUri() . $this->module->getPathUri() . 'logo.png';
    }
}
