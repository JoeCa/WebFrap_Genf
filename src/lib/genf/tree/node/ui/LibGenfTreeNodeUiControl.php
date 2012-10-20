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
 * 
 * @example Beispiel Tag
 * 
 * <control location="sub_panel" type="checkbox" > 
 * 
 *   <label>
 *     <text lang="de" >Ein Filter</text>
 *     <text lang="en" >A Filter</text>
 *   </label>
 *   
 *   <!-- 
 *   Standard ist inactive
 *    -->
 *   <default>active</default>
 *   
 * </control>
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiControl
  extends LibGenfTreeNode
{

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameControl( $this->node );

  }//end protected function loadChilds */
  
  
  /**
   * Die Location abfragen, 
   * Das ist die information in welchem UI Teil das Element positioniert werden
   * soll
   * 
   * @return string
   */
  public function getLocation()
  {
    
    if( !isset( $this->node['location'] ) )
      return null;
      
    return strtolower( trim( $this->node['location'] ) );
    
  }//end public function getLocation */
  
  /**
   * Erfragen des Types des Control Elements
   * Standard ist checkbutton
   * @return string
   */
  public function getType()
  {
    
    if( !isset( $this->node['type'] ) )
      return 'checkbutton';
      
    return strtolower(trim($this->node['type']));
    
  }//end public function getType */
  
  /**
   * Prüfen ob das Controlelement standardmäßig aktiviert ist
   * 
   * @return boolean
   */
  public function defActive()
  {
    if( !isset( $this->node->default ) )
      return false;
      
    if( 'active' == trim($this->node->default) )
    {
      return true;
    }
    else 
    {
      return false;
    }
    
  }//end public function defActive */
  
  /**
   * Der Visibilität des Buttons kann auf bestimmte Profile beschränkt werden
   * 
   * @return array
   */
  public function getProfiles()
  {
    
    if( !isset( $this->node->profiles->profile ) )
      return array();
      
    $profiles = array();
    
    foreach( $this->node->profiles->profile as $profile )
    {
      $profiles[trim($profile['name'])] = trim($profile['name']);
    }
    
    return $profiles;
    
  }//end public function getProfiles */

}//end class LibGenfTreeNodeUiControls

