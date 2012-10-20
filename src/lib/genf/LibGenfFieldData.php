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
class LibGenfFieldData
  extends TArray
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Nameobject des Attributs
   * @var LibGenfNameAttribute
   */
  public $attrName   = null;
  
  /**
   * Der Original Name des Attributes
   * @var string
   */
  public $origName   = null;
  
  /**
   * Name der Tabelle
   * @var string
   */
  public $origTable      = null;
  
  
  /**
   * Name der Tabelle
   * @var string
   */
  public $table      = null;
  
  /**
   * Type des UI Elements
   * @var string
   */
  public $fieldType  = null;
  
  /**
   * Das XML Element aus dem Modell ( hoffentlich )
   * @var SimpleXMLElement
   */
  public $fieldNode  = null;
  
  /**
   * 
   * Enter description here ...
   * @var unknown_type
   */
  public $uiType     = null;
  
  /**
   * 
   * Enter description here ...
   * @var unknown_type
   */
  public $type       = null;
  
  /**
   * 
   * Enter description here ...
   * @var unknown_type
   */
  public $i18nKey    = null;
  
  /**
   * 
   * Enter description here ...
   * @var unknown_type
   */
  public $label      = null;
  
  /**
   * 
   * Enter description here ...
   * @var unknown_type
   */
  public $action     = null;
  
  /**
   * Das Attribute Object
   * @var LibGenfTreeNodeAttribute
   */
  public $obj        = null;

  /**
   * Einfache Actions wie edit oder show
   * @var string
   */
  public $fieldAction = null;
  
  /**
   * Erweiterter Action trigger
   * @var LibGenfTreeNodeTriggerAction
   */
  public $actionTrigger = null;

  /**
   * @var string
   */
  public $embeded    = null;
  
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
  
}//end class LibGenfFieldData

