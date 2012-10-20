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
 * Alle relevanten Namenselemente zum benamen der Action relevanten
 * Architekturelemente im Code
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameAction_Dataset
  extends LibGenfNameAction
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $name  = null;
  
  /**
   * @var string
   */
  public $label  = null;
  
  /**
   * @var string
   */
  public $key   = null;
  
  /**
   * @var string
   */
  public $class = null;
  
  /**
   * @var string
   */
  public $customModul = null;
  
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
    $this->key    = SParserString::subToCamelCase($this->name,true);
    $this->module = SParserString::getDomainName($this->name);
    $this->label  = LibGenfBuild::getInstance()->interpreter->getLabel( $name, 'en', true );
    $this->model  = SParserString::subToCamelCase( SParserString::removeFirstSub( $this->name ) ) ;
    
    if( isset( $name['module'] ) )
    {
      $this->customModul    = ucfirst(trim($name['module']));
    }
    else 
    {
      $this->customModul    = $this->module;
    }
    
  }//end public function parse */


}//end class LibGenfNameAction_Dataset

