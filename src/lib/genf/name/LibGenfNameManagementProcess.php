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
class LibGenfNameManagementProcess
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  public $name = null;

  /**
   *
   * @var string
   */
  public $as = null;

  /**
   *
   * @var string
   */
  public $class = null;

  /**
   *
   * @var string
   */
  public $alias = null;

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
  public function parse( $node , $params = array() )
  {

    $this->name       = trim($node['name']);
    $this->as         = trim($node['as']);
    
    $this->class      = SParserString::subToCamelCase( $this->name );
    $this->alias      = SParserString::subToCamelCase( $this->as );

    if( isset( $node['reference'] ) )
    {
      $this->reference  = trim( $node['reference'] );
      $this->entityKey  = SParserString::subToCamelCase( $this->reference );
    }

  }//end public function parse */

}//end class LibGenfNameManagementProcess

