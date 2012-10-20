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
class LibGenfNameComponent
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  public $name = null;
  
  public $source = null;
  
  public $class = null;
  
  public $sourceKey = null;
  
  public $module = null;
  
  public $customModul = null;
  
  public $unit = null;
  
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $name, $params = array()  )
  {
    $this->parse( $name, $params );
  }//end public function __construct */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $node , $params = array() )
  {

    $this->name       = trim($node['name']);
    $this->source     = trim($node['src']);

    //$this->label    = $label;
    $this->class      = SParserString::subToCamelCase( $this->name );
    $this->sourceKey  = SParserString::subToCamelCase( $this->source );
    
    $this->module     = SParserString::getDomainName( $this->name );
    
    $tmp = explode('_',$this->name);
    array_shift($tmp);

    $this->model = SParserString::subToCamelCase
    ( 
      SParserString::removeFirstSub( $this->name ) 
    );
    
    if( isset( $node['module'] ) )
    {
      $this->customModul = ucfirst( trim($node['module']) );
    }
    else 
    {
      $this->customModul = $this->module;
    }

    if( isset( $node->head['unit'] ) )
    {
      $this->unit = trim( $node->head['unit'] );
    }
    else
    {
      $this->unit = 'px';
    }


  }//end public function parse */


}//end class LibGenfNameComponent

