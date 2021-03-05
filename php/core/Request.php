<?php

namespace core;

class Request {

  public const REQUEST_KEY_TYPE = 't';
  public const REQUEST_KEY_MODULE = 'm';

  /*
   * Only 2 Methods for now
   */
  public const METHOD_GET = false;
  public const METHOD_POST = true;
  /*
   * Client wants to load the entire Website
   */
  public const TYPE_PAGE = 0;
  /*
   * Client wants to load part of the Website via ajax
   */
  public const TYPE_LOAD = 1;
  /*
   * Client wants to get raw data or tries to trigger something in the Background with ajax
   */
  public const TYPE_REST = 2;
  /*
   * Client requests additional Resources like CSS or Scripts
   */
  public const TYPE_RESOURCE = 3;

  /*
   * Which HTTP Method was used for the request
   */

  private bool $bolMethod;
  /*
   * The type specifies why the request was sent
   */
  private int $intType;
  private string $strModule;
  private User $objActiveUser;

  function __construct() {
    /*
     * Setting Request Type
     */
    $this->bolMethod = $_SERVER['REQUEST_METHOD'] == 'POST';
    $this->intType = filter_input(FILTER_SANITIZE_NUMBER_INT, self::REQUEST_KEY_TYPE);
    if ($this->intType === false) {
      $this->intType = self::TYPE_PAGE;
    }
    /*
     * Setting Module
     */
    $this->strModule = filter_input(FILTER_SANITIZE_STRING, self::REQUEST_KEY_TYPE);
    if ($this->strModule === false) {
      $this->strModule = '';
    }


    $bolLoggedIn = auth()->isLoggedIn();
  }

  /**
   *
   * @return bool Returns Either Request::METHOD_GET or Request::METHOD_POST
   */
  public function getRequestMethod(): bool {
    return $this->bolMethod;
  }

  /**
   * Returns the type code of the request.
   * The type is used to tell the intention of the request.
   * You can find information about the types at their constant in this class.
   * @return int type code
   */
  public function getRequestType(): int {
    return $this->intType;
  }

  public function getModule(): string {
    return $this->strModule;
  }

}
