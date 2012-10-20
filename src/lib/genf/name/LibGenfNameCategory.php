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
 * Alle relevanten Namenselemente zum benamen der Category relevanten
 * Architekturelemente im Code
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameCategory
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
    $this->parse($name, $params);
  }//end public function __construct */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $name , $params = array() )
  {

    if( is_string($name) )
    {
      $this->name     = $name;
      $this->label    = SParserString::subToName($name);
    }
    else
    {
      $this->name     = $name->getName();
      $this->label    = $name->label();
    }

    //$this->label           = $label;

    $this->entity   = SParserString::subToCamelCase($this->name);
    $this->class    = SParserString::subToCamelCase($this->name);

  }//end public function parse */


}//end class LibGenfName

