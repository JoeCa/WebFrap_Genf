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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiValue
  extends LibGenfTreeNode
{

  /**
   * @return string
   */
  public function getSize()
  {

    return isset( $this->node['size'] )
      ? trim($this->node['size'])
      : null;

  }//end public function getSize */

  /**
   * @return string
   */
  public function getType()
  {

    return isset( $this->node['type'] )
      ? trim($this->node['type'])
      : null;

  }//end public function getType */

}//end class LibGenfTreeNodeUiValue

