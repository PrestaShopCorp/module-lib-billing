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

namespace PrestaShop\PsBilling\Services;

use Module;
use PrestaShop\PsBilling\Wrappers\PsBillingAccountsWrapper;
use PrestaShop\PsBilling\Clients\BillingClient;
use PrestaShop\PsBilling\Config\Config;
use PrestaShop\PsBilling\Builder\UrlBuilder;

class PsBillingService
{
  /**
   * Created to make billing API request
   * 
   * @var BillingClient
   */
  private $billingClient;

  /**
   * Created to make billing API request
   * 
   * @var string
   */
  private $apiVersion;


  /**
   * @var PsBillingService
   */
  private $billingAccountsWrapper;

  public function __construct(
    PsBillingAccountsWrapper $billingAccountsWrapper = null,
    Module $module,
    $isSandbox = false,
    $apiVersion = 'v1',
    $billingEnv = null
  ) {

    $urlBuilder = new UrlBuilder();

    $this->setBillingClient(new BillingClient(
      $module->name,
      null,
      $urlBuilder->buildAPIUrl('prestabulle2'),
      $this->getAccessToken(),
      $isSandbox
    ));
    $this->setBillingAccountsWrapper($billingAccountsWrapper);
    $this->setApiVersion($apiVersion);
  }



  /**
   * Retrieve the Billing customer associated with the shop
   * on which your module is installed
   *
   * @return array
   */
  public function getCurrentCustomer()
  {
    return $this->getBillingClient()->retrieveCustomerById($this->getBillingAccountsWrapper()->getShopUuid(), $this->getApiVersion());
  }

  /**
   * Retrieve the Billing subscription associated with the shop
   * on which your module is installed
   *
   * @return array
   */
  public function getCurrentSubscription()
  {
    return $this->getBillingClient()->retrieveSubscriptionByCustomerId($this->getBillingAccountsWrapper()->getShopUuid(), $this->getApiVersion());
  }

  /**
   * Retrieve the plans associated to this module
   *
   * @return array
   */
  public function getModulePlans()
  {
    return $this->getBillingClient()->retrievePlans($this->getBillingAccountsWrapper()->getLanguageIsoCode(), $this->getApiVersion());
  }


  /**
   * setApiVersion
   *
   * @param  string $apiVersion
   * @return void
   */
  public function setApiVersion(string $apiVersion)
  {
    $this->apiVersion = $apiVersion;
  }
  /**
   * getApiVersion
   *
   * @return string
   */
  private function getApiVersion()
  {
    return $this->apiVersion;
  }
  /**
   * setBillingClient
   *
   * @param  BillingClient $billingClient
   * @return void
   */
  public function setBillingClient(BillingClient $billingClient)
  {
    $this->billingClient = $billingClient;
  }
  /**
   * getBillingClient
   *
   * @return BillingClient
   */
  private function getBillingClient()
  {
    return $this->billingClient;
  }
  /**
   * setBillingAccountsWrapper
   *
   * @param  PsBillingAccountsWrapper $billingAccountsWrapper
   * @return void
   */
  private function setBillingAccountsWrapper($billingAccountsWrapper)
  {
    $this->billingAccountsWrapper = $billingAccountsWrapper;
  }
  /**
   * getBillingAccountsWrapper
   *
   * @return PsBillingAccountsWrapper
   */
  private function getBillingAccountsWrapper()
  {
    return $this->billingAccountsWrapper;
  }
}
