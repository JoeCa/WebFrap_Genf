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
class LibGenfTreeNodeHeadProfile
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDefault($this->node);

  }//end protected function loadChilds */


/*//////////////////////////////////////////////////////////////////////////////
// grouping Methodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $type
   */
  public function inMenu( $type )
  {

    return (boolean)$this->node->xpath('//menu/'.$type);

  }//end public function inMenu */


  /**
   * @param string $type
   * /
  public function mgmtAction( $type )
  {

    $clean = $this->node->xpath('//actions[@type=\'clean\']');


    $action = $this->node->xpath('//actions/action[@type="'.$type.'"]');

    if(!$action)
      return !(boolean)count($clean);

    $action = $action[0];

    if(!isset($action['status']))
      return !(boolean)count($clean);

    if( 'false' == trim($action['status']) )
      return false;

    return true;


  }//end public function mgmtAction */

}//end class LibGenfTreeNodeHeadProfile

