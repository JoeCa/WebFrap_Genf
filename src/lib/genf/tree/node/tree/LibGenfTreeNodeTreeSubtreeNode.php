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
class LibGenfTreeNodeTreeSubtreeNode
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * Enter description here ...
   * @var string
   */
  public $type = 'subtreeNode';

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

    return 'Node'.$this->name->class;

  }//end public function getGeneratorName */

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

    if( isset($this->node->action) )
    {
      $classname   = $this->builder->getNodeClass( 'ActionTrigger' );
      return new $classname( $this->node->action );
    }
    else if( isset( $this->node->target ) )
    {
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
    }
    else 
    {
      return null;
    }

  }//end public function getAction */

  /**
   * (non-PHPdoc)
   * @see lib/genf/tree/node/menu/LibGenfTreeNodeMenuSubmenuEntry::getText()
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

}//end class LibGenfTreeNodeTreeSubtreeNode

