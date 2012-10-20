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
class LibGenfTreeNodeMenu
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDefault( $this->node );

  }//end protected function loadChilds */
  



  /**
   * @return array
   */
  public function getEntries()
  {

    $entries = array();

    if(!isset($this->node->body))
    {
      $this->builder->warn('found no entries in the menu node');
      return $entries;
    }

    $classEntry   = $this->builder->getNodeClass('TreeNode');
    $classSubmenu = $this->builder->getNodeClass('TreeSubtree');

    $children = $this->node->body->children();

    foreach( $children as $keyName => $entry )
    {

      switch ( $keyName )
      {
        case 'node':
        {
          $entries[] = new $classEntry($entry);
          break;
        }
        case 'subtree':
        {
          $entries[] = new $classSubmenu($entry);
          break;
        }
        default:
        {
          Debug::console('got nonexisting menuentry type: '. $keyName);
        }
      }

    }

    return $entries;

  }//end public function getEntries */

}//end class LibGenfTreeNodeMenu

