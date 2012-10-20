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
class LibGenfTreeNodeAccessCheck_RoleSomewhere
  extends LibGenfTreeNodeAccessCheck
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var string
   */
  public $type = 'role_somewhere';
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return array
   */
  public function getRoles()
  {

    $roles = array();

    if( !isset($this->node->roles->role) )
      return $roles;

    foreach( $this->node->roles->role as $role )
    {
      $roles[] = trim( $role['name'] );
    }

    return $roles;

  }//end public function getRoles */
  
  /**
   * @return string
   */
  public function getArea()
  {
  
    if( isset( $this->node->area ) )
    {
      return trim( $this->node->area['name'] );
    }
    
    return null;

  }//end public function getArea */

}//end class LibGenfTreeNodeAccessCheckRoleSomewhere

