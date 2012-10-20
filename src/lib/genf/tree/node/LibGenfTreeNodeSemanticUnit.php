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
class LibGenfTreeNodeSemanticUnit
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// implement parent nodes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   */
  public function getType()
  {
    return isset($this->node['type'])?trim($this->node['type']):null;
  }//end public function getType */

  /**
   *
   */
  public function getScale()
  {
    return isset($this->node['scale'])?trim($this->node['scale']):null;
  }//end public function getScale */


}//end class LibGenfTreeNodeSemanticUnit

