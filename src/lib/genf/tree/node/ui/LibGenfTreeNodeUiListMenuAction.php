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
class LibGenfTreeNodeUiListMenuAction
  extends LibGenfTreeNode
{

  /**
   *
   * @param SimpleXmlElement $node
   * @param string $action
   */
  public function __construct( $node )
  {
    parent::__construct( $node );

    $this->name = new LibGenfNameNode( $this->node );

  }//end public function __construct */

  /**
   *
   */
  public function getName()
  {

    return $this->name;

  }//end public function getName */


}//end class LibGenfTreeNodeUiListMenuAction

