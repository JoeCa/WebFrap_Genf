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
class BdlDocumentor_Profile
  extends BdlDocumentor
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param BdlFile $bdlFile
   * @return string
   */
  public function loadFile( $bdlFile )
  {
    $this->file = $bdlFile;
    $this->dom  = $this->file->getNodeByPath( '/bdl/profiles/profile' );
  }//end public function loadFile */
  
  /**
   * @param BdlFile $bdlFile
   * @return string
   */
  public function syncDocuPage( $lang )
  {

    // profil erstellen
    $page = $this->orm->getByKey( 'WbfsysDocuTree', 'wbf-profile-'.$this['name'] );
    
    if( !$page )
    {
      $page = new WbfsysDocuTree_Entity();
      $page->m_parent = $this->orm->getByKey( 'WbfsysDocuTree', 'wbf-profile' );
    }
    
    if( !$page->m_parent )
    {
      $page->m_parent = $this->orm->getByKey( 'WbfsysDocuTree', 'wbf-profile' );
    }
    
    $page->access_key = 'wbf-profile-'.$this['name'];
    
    $label = $this->getLabelByLang($lang);
    if( '' == trim($label) )
      $label = SParserString::subToName($this['name']);
    
    $page->title    = 'Profile : '.$label;
    $page->template = 'page';
    
    $page->short_desc = 'Profile : '.$label;
    $page->content    = $this->renderContent( $lang );
      
    $this->orm->save( $page );

  }//end  public function syncDocuPage */
  
  /**
   * @param BdlFile $bdlFile
   * @return string
   */
  public function renderContent( $lang )
  {

  }//end  public function renderContent */


}//end class BdlIndex_Profile
