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
class LibGenfTreeNodelistAclValue
  extends LibGenfTreeNodelist
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////
  
  protected $aclValues = array
  (
    'DENIED'  => Acl::DENIED,
    'LISTING' => Acl::LISTING,
    'ACCESS'  => Acl::ACCESS,
    'ASSIGN'  => Acl::ASSIGN,
    'INSERT'  => Acl::INSERT,
    'UPDATE'  => Acl::UPDATE,
    'DELETE'  => Acl::DELETE,
    'PUBLISH'  => Acl::PUBLISH,
    'MAINTENANCE'  => Acl::MAINTENANCE,
    'ADMIN'  => Acl::ADMIN,
  );
  
  /**
   * 
   * @param string $key
   * @return boolean
   */
  public function valueExists( $key  )
  {
    
    return isset(  $this->aclValues[strtoupper($key)] );
    
  }//end public function valueExists */

}//end class LibGenfTreeNodelistAttribute

