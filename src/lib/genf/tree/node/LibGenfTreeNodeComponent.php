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
class LibGenfTreeNodeComponent
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute, mainly for caching
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  protected $label        = null;

////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameComponent( $this->node );

  }//end protected function loadChilds */

  /**
   * @return string
   */
  public function name()
  {
    return (string)$this->node['name'];
  }//end public function name */

  /**
   * @return string
   */
  public function type()
  {
    return (string)$this->node['type'];
  }//end public function type */

  /**
   * @return string
   */
  public function src()
  {
    return (string)$this->node['src'];
  }//end public function src */

}//end class LibGenfTreeNodeComponent

