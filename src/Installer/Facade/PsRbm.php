<?php

namespace PrestaShop\PsRbmInstaller\Installer\Facade;


class PsRbm
{

    private $psAccountService;

    /**
     * Available services class names
     */
    const PS_ACCOUNTS_SERVICE = 'PrestaShop\Module\PsAccounts\Service\PsAccountsService';


    /**
     * PsAccounts constructor.
     *
     */
    public function __construct()
    {
        $this->psAccountService = $this->getPsAccountsService();
    }

    public function getShopUuidV4() {
        return $this->psAccountService->getShopUuidV4();
    }

    public function getRefreshToken() {
        return $this->psAccountService->getRefreshToken();
    }

    public function getEmail() {
        return $this->psAccountsService->getEmail();
    }

    /**
     * @param string $serviceName
     *
     * @return mixed
     *
     * @throws ModuleNotInstalledException
     * @throws ModuleVersionException
     */
    public function getService($serviceName)
    {
        return \Module::getInstanceByName('ps_accounts')
                    ->getService($serviceName);
    }

    /**
     * @return mixed
     *
     * @throws ModuleNotInstalledException
     * @throws ModuleVersionException
     */
    public function getPsAccountsService()
    {
        return $this->getService(self::PS_ACCOUNTS_SERVICE);
    }

    public function present($data) {
        return [
            'context' => [
                'versionPs' => $data['versionPs'],
                'versionModule' => $data['versionModule'],
                'moduleName' => $data['moduleName'],
                'refreshToken' => $this->getRefreshToken(),
                'emailSupport' => $data['emailSupport'],
                'shop' => [
                    'uuid' => $this->getShopUuidV4()
                ],
                'i18n' => [
                    'isoCode' => $data['isoCode']
                ],
                'user' => [
                    'createdFromIp' => $data['ipAddress'],
                    'email' => $this->getEmail()
                ]
            ]
        ];
    }
}
