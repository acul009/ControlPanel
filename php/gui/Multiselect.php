<?php

namespace gui;

/**
 * Description of Multiselect
 *
 * @author acul
 */
class Multiselect extends guiComponent {
  //put your code here

  private array $arrOptions;

  public function __construct($arrOptions) {
    $this->arrOptions = $arrOptions;
  }


  public function getHTML(): string {

  }

}
