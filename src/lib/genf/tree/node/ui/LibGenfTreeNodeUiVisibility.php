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
class LibGenfTreeNodeUiVisibility
  extends LibGenfTreeNode
{

  /**
   * Erfragen der Profile
   * @return array
   */
  public function getProfiles()
  {

    $profiles = array();

    if( !isset( $this->node->profiles->profile ) )
      return $profiles;

    foreach( $this->node->profiles->profile as $profile )
    {
      $profiles[trim($profile['name'])] = trim($profile['name']);
    }

    return $profiles;

  }//end public function getProfiles */

}//end class LibGenfTreeNodeUiValue

