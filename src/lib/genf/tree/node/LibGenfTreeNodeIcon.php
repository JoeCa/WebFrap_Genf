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
 * @subpackage GenF
 */
class LibGenfTreeNodeIcon
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * the alternativ text for the icon
   * @var string
   */
  public $alt = null;

  /**
   * icon key
   * @var string
   */
  public $key = null;

  /**
   * icon src
   * @var string
   */
  public $src = null;

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = trim($this->node['src']);
    $this->src  = trim($this->node);

    if( isset( $this->node['alt'] ) )
      $this->alt = trim($this->node['alt']);
    else if( isset( $this->node['key'] ) )
      $this->key = trim( $this->node['key'] );
    else
      $this->alt = 'undef icon';

    if( isset($this->node['key']) )
      $this->key = SParserString::subToCamelCase(trim($this->node['key'])) ;

  }//end protected function loadChilds */

  /**
   * @return string
   */
  public function getSrc()
  {
    return $this->src;
  }//end public function getSrc */

  /**
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }//end public function getKey */

  /**
   * @return string
   */
  public function getAlt()
  {
    return $this->alt;
  }//end public function getAlt */

}//end class LibGenfTreeNodeIcon

