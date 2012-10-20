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
class LibGenfNameReference
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Das Label für das Management
   * @var string
   */
  public $label;
  
  /**
   * Das Label für das Management
   * @var string
   */
  public $pLabel;

  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $node, $params = array()  )
  {
    $this->parse($node, $params);
  }//end public function __construct */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($node, $params)
   */
  public function parse( $node , $params = array() )
  {

    $this->name   = trim($node['name']);

    
    $builder = LibGenfBuild::getInstance();

    $this->label          = $builder->interpreter->getLabel( $node, $builder->langDefault, true );
    $this->pLabel         = $builder->interpreter->getPlabel( $node, $builder->langDefault, true );
    
    //$this->label  = $label;
    $this->entity   = SParserString::subToCamelCase(  $this->name );
    $this->class    = SParserString::subToCamelCase(  $this->name );

  }//end public function parse */


}//end class LibGenfNameReference

