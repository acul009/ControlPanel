<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\gui;

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
