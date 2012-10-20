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
class LibGenfTreeNodeTree
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

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

    if( !isset( $this->node->body ) )
    {
      $this->builder->warn('found no entries in the node tree');
      return $entries;
    }

    $classEntry   = $this->builder->getNodeClass( 'TreeNode' );
    $classSubmenu = $this->builder->getNodeClass( 'TreeSubtree' );

    $menuRoot     = $this->builder->getRoot('menu');

    $children = $this->node->body->children();

    foreach( $children as $keyName => $entry )
    {

      switch ( $keyName )
      {
        case 'node':
        {

          if( isset($entry['template']) )
          {
            if( !$menuEntry = $menuRoot->getMenuNode( trim( $entry['template'] ) ) )
            {
              $this->builder->warn( "Requested nonexisting menunode template ".trim($entry['template']) );
              continue;
            }
          }
          else
          {
            $menuEntry = $entry;
          }

          $entries[] = new $classEntry( $menuEntry );
          break;
        }
        case 'subtree':
        {

          if( isset($entry['template']) )
          {
            if( !$menuEntry = $menuRoot->getMenuSubtree( trim( $entry['template'] ) ) )
            {
              $this->builder->warn("Requested nonexisting subtree template ".trim($entry['template']) );
              continue;
            }
          }
          else
          {
            $menuEntry = $entry;
          }

          $entries[] = new $classSubmenu( $menuEntry );
          break;
        }
        default:
        {
          Debug::console( 'Got nonexisting menuentry type: '. $keyName );
        }
      }

    }

    return $entries;

  }//end public function getEntries */

}//end class LibGenfTreeNodeTree

