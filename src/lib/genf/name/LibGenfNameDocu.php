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
class LibGenfNameDocu
  extends LibGenfName
{
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

    $name = trim($node['name']);

    if( isset($node['module']) && trim($node['module']) != '' )
      $this->customModul = strtolower( trim($node['module']) );
    else
      $this->customModul = 'webfrap';
     
    if( isset( $node['parent'] ) )
    {
      $this->parent        = trim($node['parent']);
    }

    $this->name            = $name;
    $this->source          = $name;

    $this->module          = SParserString::getDomainName($name);

  }//end public function parse */
  

}//end class LibGenfNameDocu

