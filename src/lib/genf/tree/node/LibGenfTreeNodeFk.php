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
class LibGenfTreeNodeFk
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////


  public $attribute = null;

////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return string
   */
  public function name( $name = null )
  {
    if($name)
    {
      return $name == (string)$this->node['name'];
    }
    else
    {
      return (string)$this->node['name'];
    }

  }//end public function name */

  /**
   * check if the attribute has a target, and if yes return it
   * @return string
   */
  public function target( )
  {

    return isset($this->node['target'])
      ? (string)$this->node['target']
      : null;

  }//end public function target */

  /**
   *
   * @return string
   */
  public function fullName()
  {
    return $this->attribute->entity->name->name.'_'.$this->attribute->name->name.'_to_'.$this->target().'_rowid';
  }//end public function fullName */

  /**
   *
   * @return string
   */
  public function onUpdate()
  {
    return 'setNull';
  }//end public function onUpdate */

  /**
   *
   * @return string
   */
  public function onDelete()
  {
    return 'setNull';
  }//end public function onDelete */

////////////////////////////////////////////////////////////////////////////////
// implement parent nodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @overwrite should be implemented if needed
   * @return unknown_type
   */
  protected function prepareNode( $params = array() )
  {

    // the first attribute should be the entity
    $this->attribute   = $params['attribute'];

    // ok little redundant but whatever...
    $this->name = new LibGenfName();
    $this->name->name     = $this->name();
    $this->name->fullName = $this->fullName();
    $this->name->target   = $this->target();

  }//end protected function prepareNode */


}//end class LibGenfTreeNodeFk

