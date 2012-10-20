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
 * Alle relevanten Namenselemente zum benamen der Message relevanten
 * Architekturelemente im Code
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameMessage
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Name der Nachricht zum eindeutigen identifiezieren eines Nachrichten
   * Knotens
   * 
   * @var string
   */
  public $name  = null;
  
  /**
   * Key über welchen die Nachricht auf eine Variable gebunden werden kann
   * @var string
   */
  public $key   = null;
  
  /**
   * Der Domainteil der Klasse
   * @var string
   */
  public $class = null;
  
  /**
   * Der Name des Modules 
   * Case Sensitiv
   * Bsp: Wbfsys
   * 
   * @var string
   */
  public $module = null;
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $name, $params = array()  )
  {
    $this->parse($name, $params);
  }//end public function __construct */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $name , $params = array() )
  {

    $this->name   = trim($name['name']);
    
    $this->class  = SParserString::subToCamelCase($this->name);
    
    if( isset( $name['extends'] ) )
      $this->extends  = SParserString::subToCamelCase( trim( $name['extends'] ) );

    if( isset( $name['module'] ) )
    {
      $this->module         = ucfirst(trim($name['module']));
    }
    else 
    {
      $this->module         = SParserString::getDomainName($this->name);
    }
    
  }//end public function parse */


}//end class LibGenfNameAction

