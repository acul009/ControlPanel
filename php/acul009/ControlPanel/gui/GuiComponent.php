<?php

declare(strict_types=1);

namespace acul009\ControlPanel\gui;

/**
 * Description of guiComponent
 *
 * @author acul
 */
abstract class GuiComponent {

    public abstract function getHTML(): string;
}
