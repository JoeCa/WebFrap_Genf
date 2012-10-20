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
class LibGenfTreeNodeProcessEdge
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var int
   */
  public $order = null;
  
  /**
   * @var LibGenfTreeNodeColorScheme
   */
  public $colorSchema = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getTarget()
  {

    return trim($this->node['target']);

  }//end public function getTarget */
  
  /**
   * @return boolean
   */
  public function confirmationRequired()
  {

    return isset( $this->node->confirm );

  }//end public function confirmationRequired */

  /**
   * @return string
   */
  public function getDirection()
  {

    if( isset($this->node['direction']) )
    {
      return trim($this->node['direction']);
    }
    else
    {
      return null;
    }

  }//end public function getDirection

  /**
   * @return string
   */
  public function getIcon()
  {

    if( isset($this->node['icon']) )
    {
      return trim($this->node['icon']);
    }
    elseif( isset($this->node['direction']) && 'back' == trim($this->node['direction'])  )
    {
      return 'process/back.png';
    }

    return null;

  }//end public function getIcon

  /**
   * @return string
   */
  public function getColor()
  {

    return isset($this->node->color)
      ? trim( $this->node->color )
      : 'default';

  }//end public function getColor */
  
  /**
   * @return LibGenfTreeNodeColorScheme
   */
  public function getColorScheme()
  {

    if( !is_null($this->colorSchema) )
      return null;
    
    if( !isset( $this->node->color ) )
      return null;
    
    $this->colorSchema = new LibGenfTreeNodeColorScheme( $this->node->color );
    
    return $this->colorSchema;

  }//end public function getColorScheme */

  /**
   * @return int
   */
  public function getOrder()
  {

    return $this->order;

  }//end public function getOrder */

  /**
   * @param int $order
   */
  public function setOrder( $order )
  {

    $this->order = $order;

  }//end public function setOrder */
  
  /**
   * @return string
   */
  public function getAccessKey()
  {

    if( isset( $this->node->access['key'] ) )
    {
      return trim($this->node->access['key']);
    }
    else 
    {
      return null;
    }
    

  }//end public function getAccessKey */

  /**
   * @return array
   */
  public function getRoles()
  {

    $roles = array();

    if( isset( $this->roles->role ) )
    {
      foreach( $this->roles->role as $role )
      {
        $roles[] = trim( $role['name'] );
      }
    }

    return $roles;

  }//end public function getRoles */

  /**
   * @return array
   */
  public function getProfiles()
  {

    $profiles = array();

    if( isset( $this->profiles->profile ) )
    {
      foreach( $this->profiles->profile as $profile )
      {
        $profiles[] = trim( $profile['name'] );
      }
    }

    return $profiles;

  }//end public function getProfiles */

  /**
   * @return array
   */
  public function getConstraints()
  {

    $constraints = array();

    if( isset( $this->constraints->constraint ) )
    {
      foreach( $this->constraints->constraint as $constraint )
      {
        $constraints[] = trim( $constraint['name'] );
      }
    }

    return $constraints;

  }//end public function getConstraints */
  
  /**
   * @return array
   */
  public function getActions()
  {

    $actions = array();

    if( isset( $this->actions->action ) )
    {
      foreach( $this->actions->action as $action )
      {
        $actions[trim($action['position'])] = trim($action['name']);
      }
    }

    return $actions;

  }//end public function getActions */


}//end class LibGenfTreeNodeProcessEdge

