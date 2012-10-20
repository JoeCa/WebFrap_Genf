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
class LibGenfNameEnumValue
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * @var string
   */
  public $name     = null;
  
  /**
   * @var string
   */
  public $key      = null;

  /**
   * @var string
   */
  public $label    = null;
  
  /**
   * @var string
   */
  public $value    = null;
  
////////////////////////////////////////////////////////////////////////////////
// attributes
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
  public function parse( $node , $params = array() )
  {

    $name           = trim( $node['name'] );

    $this->name     = $name;
    $this->key      = strtoupper( $name );
    $this->label    = LibGenfBuild::getInstance()->interpreter->getLabel( $node, 'en', true );
    
    $this->value    = trim( $node['value'] );

  }//end public function parse */

}//end class LibGenfNameProcess

