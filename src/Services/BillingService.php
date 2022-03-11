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

use PrestaShop\PsBilling\Clients\BillingClient;
use PrestaShop\PsBilling\Exceptions\BillingApiException;

class BillingService
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


  public function __construct(string $apiVersion = 'v1')
  {
    // TODO
    // récupérer le ps_account service pour avoir le token
    // récupérer le shop id pour avoir le customer
    // récupérer la langue de l'utilisateur
    // récupérer le nom du module
    // récupérer l'apiUrl en se basant sur l'environnement du contexte
    // récupérer l'info de sandbox dans le contexte
    $this->setBillingClient(new BillingClient(
      'rbm_example',
      null,
      'http://localhost:3000',
      'eyJhbGciOiJSUzI1NiIsImtpZCI6Ijk1NmMwNDEwZmE1MjFjMTZlNDQ2NWE4ZjVjODU5NjZhNWY1MDk5NGIiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL3NlY3VyZXRva2VuLmdvb2dsZS5jb20vcHJlc3Rhc2hvcC1yZWFkeS1pbnRlZ3JhdGlvbiIsImF1ZCI6InByZXN0YXNob3AtcmVhZHktaW50ZWdyYXRpb24iLCJhdXRoX3RpbWUiOjE2Mzg4MDgyNzAsInVzZXJfaWQiOiJiMjU4MWU0Yi0wMDMwLTRmYzgtOWJmMi03ZjAxYzU1MGE5NDYiLCJzdWIiOiJiMjU4MWU0Yi0wMDMwLTRmYzgtOWJmMi03ZjAxYzU1MGE5NDYiLCJpYXQiOjE2Mzk0ODc3ODQsImV4cCI6MTYzOTQ5MTM4NCwiZW1haWwiOiJodHRwdGRhdmVhdWF6ZXJ0eWRlbW9jcmF0aWtwcmVzdGFzaG9wbmV0MUBzaG9wLnByZXN0YXNob3AuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImZpcmViYXNlIjp7ImlkZW50aXRpZXMiOnsiZW1haWwiOlsiaHR0cHRkYXZlYXVhemVydHlkZW1vY3JhdGlrcHJlc3Rhc2hvcG5ldDFAc2hvcC5wcmVzdGFzaG9wLmNvbSJdfSwic2lnbl9pbl9wcm92aWRlciI6ImN1c3RvbSJ9fQ.JKXB0ynGFiwHrVs97-tNq94fP-igphII39mjpdz9YHtPBChB_oWZ9P0JFGcRx6CCKXdN9AasNZ4_I_OTyWfpVjqjuSnUZelgufDp88upGUReN-pSvf8VdqeY3P4TIJJVz6sVYmSLrGuu-OJPxr7RtzrgczFIyhNs8TInu4BMRDzNaKv6wqR7DJvWljDTW27Oa1RZCFbQcanZpXCAZg-6lszaw77cAViPsZcc4TDFU7niq5fXrFXv5OtaWcUOMd19Y_TewZkEys4-Ssr3hbdENk1DBKO0jzlPOI_idGaNeNI-57N8y8-tatf3sftYXqhnKr61Ng6AtqVJs5adtRNf0g'
    ));
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
    return $this->getBillingClient()->retrieveCustomerById('b2581e4b-0030-4fc8-9bf2-7f01c550a946', $this->getApiVersion());
  }

  /**
   * Retrieve the Billing subscription associated with the shop
   * on which your module is installed
   *
   * @return array
   */
  public function getCurrentSubscription()
  {
    return $this->getBillingClient()->retrieveSubscriptionByCustomerId('b2581e4b-0030-4fc8-9bf2-7f01c550a946', $this->getApiVersion());
  }

  /**
   * Retrieve the plans associated to this module
   *
   * @return array
   */
  public function getModulePlans(string $lang)
  {
    return $this->getBillingClient()->retrievePlans($lang, $this->getApiVersion());
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
   * getApiVersion
   *
   * @return string
   */
  private function getApiVersion()
  {
    return $this->apiVersion;
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
}
