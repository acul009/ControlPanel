<?php

declare(strict_types=1);

namespace utils;

use Initiator;

class TemplateFiller {

    private string $strTemplate;
    private array $arrSubstitute;

    private const BRACKET_OPEN = '{';
    private const BRACKET_CLOSED = '}';

    public function __construct(string $strTemplate) {
        $this->loadTemplate($strTemplate);
    }

    public static function loadFromFile(string $strFilename, string $strModule = ''): self {
        $strFilePath = \Initiator::active()->Library()->getWorkingDir() . '/templates/' . $strFilename . '.html';
        return new self(file_get_contents($strFilePath));
    }

    private function loadTemplate(string $strTemplate): void {
        $this->strTemplate = $strTemplate;
        $this->arrSubstitute = [];

        $intBracketOpenPos = strpos($this->strTemplate, self::BRACKET_OPEN);
        while ($intBracketOpenPos !== false) {

            $intBracketClosedPos = strpos($this->strTemplate, self::BRACKET_CLOSED, $intBracketOpenPos);

            if ($intBracketClosedPos === false) {
                throw new Exception('Template missing closing bracket');
            }
            $intCutFrom = $intBracketOpenPos + strlen(self::BRACKET_OPEN);
            $intCutLength = $intBracketClosedPos - $intCutFrom;
            $strReplace = substr($this->strTemplate, $intCutFrom, $intCutLength);
            $this->arrSubstitute[$strReplace] = '';

            $intBracketOpenPos = strpos($this->strTemplate, self::BRACKET_OPEN, $intBracketClosedPos);
        }
    }

    public function getSubsituteArray(): array {
        return $arrSubstitute;
    }

    public function setSubstituteArray(array $arrSubstitute): void {
        $this->arrSubstitute = $arrSubstitute;
    }

    public function __toString() {
        $strFilledTemplate = $this->strTemplate;
        foreach ($this->arrSubstitute as $strKey => $strValue) {
            $strReplaceMarker = self::BRACKET_OPEN . $strKey . self::BRACKET_CLOSED;
            $strFilledTemplate = str_replace($strReplaceMarker, $strValue, $strFilledTemplate);
        }
        return $strFilledTemplate;
    }

}
