<?php
/*******************************************************************************
*
* @author      : Dominik Bonsch <dominik.bonsch@webfrap.net>
* @date        :
* @copyright   : Webfrap Developer Network <contact@webfrap.net>
* @project     : Webfrap Web Frame Application
* @projectUrl  : http://webfrap.net
*
* @licence     : BSD License see: LICENCE/BSD Licence.txt
* 
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/


/**
 * Eine Name Lib für die Namings
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfFormFieldData
  extends TArray
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var TContextAttribute
   */
  public $field   = null;
  
  /**
   * @var string
   */
  public $sourceName = null;
  
  /**
   * @var string
   */
  public $sourceKey  = null;
  
  /**
   * @var string
   */
  public $ident   = null;
  
  /**
   * @var string
   */
  public $keyName      = null;
  
  /**
   * @var string
   */
  public $label      = null;

  /**
   * @var string
   */
  public $i18nTitle      = null;
  
  /**
   * @var string
   */
  public $i18nLabel      = null;
  
  
  /**
   * @var string
   */
  public $itemName      = null;

  /**
   * @var string
   */
  public $itemId     = null;
  
  /**
   * @var string
   */
  public $plainId     = null;
  
  /**
   * @var string
   */
  public $itemTemplate     = null;

  /**
   * @var string
   */
  public $class     = null;
  
  /**
   * @var string
   */
  public $width     = null;
  
  /**
   * Gibt Debugdaten zurück
   */
  public function debugData()
  {
    
    return "Name:'{$this->attrName}' OrigName:'{$this->origName}' Table:'{$this->table}' OrigTable:'{$this->origTable}' Embeded:'{$this->embeded}' Trace: ".Debug::backtrace();
  }//end public function debugData */
  
  /**
   * To String Methode 
   * wird zum debugging verwendet
   */
  public function __toString()
  {
    return $this->debugData();
  }//end public function __toString */
  
}//end class LibGenfName

