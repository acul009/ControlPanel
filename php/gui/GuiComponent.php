<?php

declare(strict_types=1);

namespace gui;

/**
 * Description of guiComponent
 *
 * @author acul
 */
abstract class GuiComponent {

    public abstract function getHTML(): string;
}
