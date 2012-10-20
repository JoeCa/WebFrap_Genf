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
 * @package WebFrap
 * @subpackage Genf
 */
class LibCartridgeSubComponent
  extends LibCartridgeSubparser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  protected $className    = null;

  /**
   * name object for the component
   * @var LibGenfNameComponent
   */
  protected $compName     = null;

  /**
   * name object for the component
   * @var LibGenfGenerator
   */
  protected $generator    = null;

  /**
   * name object for the component
   * @var array
   */
  protected $genfCode     = array();

  /**
   * name object for the component
   * @var array
   */
  protected $handCode     = array();

  /**
   * name object for the component
   * @var array
   */
  protected $genfTemplate = array();

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @overwrite
   * @return string
   */
  public function parse()
  {
    $this->render_GenfClass();
    $this->render_HandCodeClass();
    return true;
  }//end public function parserGenf */

  /**
   * @param $name
   */
  public function setName( $name )
  {
    $this->name  = $name;

    if( !isset( $this->genfCode[$this->name->lower('module')] ) )
    {
      $this->genfCode[$this->name->lower('module')]     = array();
      $this->handCode[$this->name->lower('module')]     = array();
      $this->genfTemplate[$this->name->lower('module')] = array();
    }

  }//end public function setName */


  /**
   *
   * @return string
   */
  public function parseQuery()
  {
    return null;
  }//end public function parseQuery */

  /**
   * @return string
   */
  public function className()
  {
    return $this->name->class.'_'.$this->className;
  }//end public function className */

  /**
   * @return string
   */
  public function genfClassName()
  {
    return $this->className.$this->name->class.'Genf';
  }//end public function genfClassName */

  /**
   * @return array
   */
  public function getHandClasses()
  {
    return $this->handCode;
  }//end public function getHandClasses */

  /**
   *
   * @return array
   */
  public function getGenfClasses()
  {
    return $this->genfCode;
  }//end public function getGenfClasses */

  /**
   *
   * @return array
   */
  public function getGenfTemplates()
  {
    return $this->genfTemplate;
  }//end public function getGenfTemplates */


  /**
   * clean the parsed elements in
   * @return void
   */
  public function clean()
  {

    $this->genfCode     = array();
    $this->handCode     = array();
    $this->genfTemplate = array();

  }//end public function clean */

}//end class LibCartridgeSubComponent

