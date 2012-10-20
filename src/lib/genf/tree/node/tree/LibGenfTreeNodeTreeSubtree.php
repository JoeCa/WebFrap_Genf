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
class LibGenfTreeNodeTreeSubtree
  extends LibGenfTreeNode
{


  /**
   * Der Type des Menunodes
   * @var string
   */
  public $type = 'subtree';


  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameTyped( $this->node );

  }//end protected function loadChilds */


  /**
   * @return string
   */
  public function getType()
  {

    return $this->type;

  }//end public function getType */

 /**
   * @return string
   */
  public function getMethodName()
  {

    if( $this->name->class )
      return $this->type.$this->name->class;
    else
      return $this->type.$this->name->key;

  }//end public function getMethodName */

  /**
   * @return string
   */
  public function getGeneratorName()
  {

    return ucfirst($this->type).$this->name->class;

  }//end public function getGeneratorName */

  /**
   * @return array[LibGenfTreeNode]
   */
  public function getEntries()
  {

    $entries = array();

    if( !isset( $this->node->body ) )
    {
      $this->builder->warn( 'found no entries in the menu Subtree' );
      return $entries;
    }

    $classEntry   = $this->builder->getNodeClass( 'TreeSubtreeNode' );
    $classSubmenu = $this->builder->getNodeClass( 'TreeSubtree' );

    $menuRoot     = $this->builder->getRoot( 'menu' );

    $children     = $this->node->body->children();

    foreach( $children as $keyName => $entry )
    {

      switch ( $keyName )
      {
        case 'node':
        {

          if( isset($entry['template']) )
          {
            if(!$menuEntry = $menuRoot->getMenuNode(trim($entry['template'])))
            {
              $this->builder->warn("Requested nonexisting menunode template ".trim($entry['template']) );
              continue;
            }
          }
          else
          {
            $menuEntry = $entry;
          }

          $entries[] = new $classEntry($menuEntry);
          break;
        }
        case 'subtree':
        {

          if( isset($entry['template']) )
          {
            if(!$menuEntry = $menuRoot->getMenuSubtree(trim($entry['template'])))
            {
              $this->builder->warn("Requested nonexisting subtree template ".trim($entry['template']) );
              continue;
            }
          }
          else
          {
            $menuEntry = $entry;
          }

          $entries[] = new $classSubmenu($menuEntry);
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

  /**
   *
   * @return LibGenfTreeNodeIcon
   */
  public function getIcon()
  {

    if(!isset($this->node->icon))
      return null;

    $classname   = $this->builder->getNodeClass('Icon');

    return new $classname( $this->node->icon );

  }//end public function getIcon */

  /**
   * @return LibGenfTreeNodeActionTrigger
   */
  public function getAction()
  {

    if(!isset($this->node->action))
      return null;

    $classname   = $this->builder->getNodeClass( 'ActionTrigger' );

    return new $classname( $this->node->action );

  }//end public function getAction */

  /**
   *
   * Enter description here ...
   */
  public function getText()
  {
    return $this->name->label;
  }//end public function getText */

  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {

    if(!isset($this->node->access) )
      return null;

    $classname   = $this->builder->getNodeClass('Access');

    return new $classname( $this->node->access );

  }//end public function getAccess */

  /**
   * @return LibGenfTreeNodeActionTarget
   */
  public function getTarget()
  {

    if( !isset( $this->node->target ))
      return null;

    $classname   = $this->builder->getNodeClass('ActionTarget');

    try
    {
      $obj = new $classname( $this->node->target );
      return $obj;
    }
    catch( LibGenfModel_Exception $e )
    {
      return null;
    }


  }//end public function getTarget */


}//end class LibGenfTreeNodeTreeSubtree

