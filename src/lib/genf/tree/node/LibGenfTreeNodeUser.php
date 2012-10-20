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
class LibGenfTreeNodeUser
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var string
   */
  public $firstname = null;
  
  /**
   * @var string
   */
  public $lastname = null;
  
  /**
   * @var string
   */
  public $password = null;
  
  /**
   * @var string
   */
  public $employee = null;
  
  /**
   * @var string
   */
  public $nonCertLogin = null;
  
  /**
   * @var string
   */
  public $academicTitle = null;
  
  /**
   * @var string
   */
  public $noblesseTitle = null;
  
  /**
   * @var string
   */
  public $description = null;
  
  /**
   * @var string
   */
  public $mainProfile = null;
  
  /**
   * @var string
   */
  public $userLevel = null;
  
  /**
   * @var array
   */
  public $profiles = array();
  
  /**
   * @var array
   */
  public $contactItems = array();
  
  /**
   * @var array
   */
  public $roles = array();
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameUser( $this->node );
    
    $this->firstname = addslashes( trim( $this->node->firstname ) );
  
    $this->lastname = addslashes( trim( $this->node->lastname ) );
  
    $this->password = addslashes( trim( $this->node->password ) );
  
    $this->employee = addslashes( trim( $this->node->employee ) );
  
    $this->nonCertLogin = addslashes( trim( $this->node->non_cert_login ) );
  
    $this->academicTitle = addslashes( trim( $this->node->academic_title ) );
  
    $this->noblesseTitle = addslashes( trim( $this->node->noblesse_title ) );
  
    $this->description = addslashes( trim( $this->node->description ) );
  
    $this->mainProfile = trim( $this->node->main_profile );
  
    $this->userLevel = strtoupper(trim( $this->node->user_level ));
    
    if( isset( $this->node->roles->role ) )
    {
      foreach( $this->node->roles->role as $role )
      {
        $this->roles[] = trim($role['name']);
      }
    }
    
    if( isset( $this->node->profiles->profile ) )
    {
      foreach( $this->node->profiles->profile as $profile )
      {
        $this->profiles[] = trim($profile['name']);
      }
    }
    
    if( isset( $this->node->contact->item ) )
    {
      foreach( $this->node->contact->item as $item )
      {
        $this->contactItems[] = array( 'value' => trim($item['value']), 'type' => trim($item['type'])  );
      }
    }

  }//end protected function loadChilds */



}//end class LibGenfTreeNodeUser

