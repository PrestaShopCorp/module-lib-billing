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

namespace PrestaShop\PsRbmInstaller\Installer\Facade;

use Tools;
use Module;
use PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts;

class PsRbm
{
    const PS_ACCOUNTS_MODULE_NAME = 'ps_accounts';

    /**
     * Available services class names
     */
    const PS_ACCOUNTS_SERVICE = 'PrestaShop\Module\PsAccounts\Service\PsAccountsService';


    private $psAccountService;

    /**
     * @var \Module
     */
    private $module;

    /**
     * @var \Context
     */
    private $context;


    /**
     * Presenter constructor.
     *
     * @param  \Module $module
     * @param \Context|null $context
     */
    public function __construct(
        \Module $module,
        PsAccounts $accountFacade = null,
        \Context $context = null)
    {
        if (null === $context) {
            $context = \Context::getContext();
        }
        $this->context = $context;
        $this->module = $module;

        $this->psAccountService = ($accountFacade) ? $accountFacade->getPsAccountsService() : $this->getPsAccountsService();
    }

    private function getShopUuid() {
        return $this->psAccountService->getShopUuidV4();
    }

    private function getRefreshToken() {
        return $this->psAccountService->getRefreshToken();
    }

    private function getEmail() {
        return $this->psAccountService->getEmail();
    }

    private function getAccountInstance() {
        return \Module::getInstanceByName(self::PS_ACCOUNTS_MODULE_NAME);
    }

    /**
     * @param string $serviceName
     *
     * @return mixed
     *
     * @throws ModuleNotInstalledException
     * @throws ModuleVersionException
     */
    private function getAccountService($serviceName)
    {
        return $this->getAccountInstance->getService($serviceName);
    }

    /**
     * @return mixed
     *
     * @throws ModuleNotInstalledException
     * @throws ModuleVersionException
     */
    private function getPsAccountsService()
    {
        return $this->getAccountService(self::PS_ACCOUNTS_SERVICE);
    }

    /**
     * Get the isoCode from the context language, if null, send 'en' as default value
     *
     * @return string
     */
    private function getLanguageIsoCode()
    {
        return $this->context->language !== null ? $this->context->language->iso_code : 'en';
    }

    /**
     * Get the isoCode from the context language, if null, send 'en' as default value
     *
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
        return $this->getShopUri().$this->module->getPathUri().'logo.png';
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->module->container->getContainer()->getParameter($name);
    }

    public function present($params) {

        return [
            // TODO
            'isSandbox' => (bool) $params['sandbox'] ?? false,
            'billingEnv' => $params['billingEnv'] ?? 'stable',

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
                'email' => $this->getEmail()
            ],

            'partnerLogo' => $params['logo'] ?? '',
            'moduleLogo' => $this->getModuleLogo(),
            'moduleTosUrl' => $params['tosLink'] ?? '',
            'emailSupport' => $params['emailSupport'] ?? ''
        ];
    }
}
