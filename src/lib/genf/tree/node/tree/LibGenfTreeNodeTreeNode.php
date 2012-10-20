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
class LibGenfTreeNodeTreeNode
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

 /**
   *
   * @var string
   */
  public $type = 'node';


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
  public function getType( $upper = false )
  {

    if( $upper )
      return ucfirst( $this->type );
    else
      return $this->type;

  }//end public function getType */

  /**
   *
   */
  public function getNodeType()
  {
    return isset( $this->node['type'] )
      ? trim($this->node['type'])
      : null;

  }//end public function getNodeType */

  /**
   */
  public function hasTarget()
  {
    return isset($this->node->target);
  }//end public function hasTarget */


  /**
   * @return LibGenfTreeNodeActionTarget
   */
  public function getTarget()
  {

    if( !isset( $this->node->target ) )
      return null;

    $classname   = $this->builder->getNodeClass( 'ActionTarget' );

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
   *
   * @return LibGenfTreeNodeIcon
   */
  public function getIcon()
  {

    if( !isset($this->node->icon) )
      return null;

    $classname   = $this->builder->getNodeClass( 'Icon' );

    return new $classname( $this->node->icon );

  }//end public function getIcon */

  /**
   * @return LibGenfTreeNodeActionTrigger
   */
  public function getAction()
  {

    if( !isset($this->node->action) )
      return null;

    $classname   = $this->builder->getNodeClass( 'ActionTrigger' );

    return new $classname( $this->node->action );

  }//end public function getAction */

  /**
   * (non-PHPdoc)
   * @see lib/genf/tree/node/LibGenfTreeNodeMenuEntry::getText()
   */
  public function getText()
  {
    return $this->name->label;
  }//end public function getText */

  /**
   * @return string
   */
  public function getUiType()
  {

    if(!isset($this->ui['type']))
      return 'button';

    return trim($this->ui['type']);

  }//end public function getType */


}//end class LibGenfTreeNodeTreeNode

