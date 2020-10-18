<?php

/**
 * Description of TemplateManager
 *
 * @author acul
 */
class TemplateFiller {

  private string $strTemplate;
  private array $arrSubstitute;

  private const BRACKET_OPEN = '{';
  private const BRACKET_CLOSED = '}';

  public function __construct(string $strTemplateName, string $strModule = '') {
    $this->loadTemplate($strTemplateName, $strModule);
  }

  private function loadTemplate(string $strTemplateName, string $strModule = null): void {
    $this->strTemplate = Initiator::active()->Library()->loadFile('template', $strTemplateName, $strModule);
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
