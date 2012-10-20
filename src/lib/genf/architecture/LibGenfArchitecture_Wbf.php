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
class LibGenfArchitecture_Wbf
  extends LibGenfArchitecture
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Die Klasse die bei List Nav Actions Standardmäßig dazu kommt
   * @var string
   */
  public $listNavActionClass = null;
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////
  
  
  /**
   * @return string
   */
  public function getListNavActionClass()
  {
    
    if( !is_null($this->listNavActionClass) )
      return $this->listNavActionClass;
    
    $navType = $this->builder->getArchitectureFlag( 'list', 'entry_controls', 'splitbutton' );

    if( 'splitbutton' == $navType )
    {
      $this->listNavActionClass = '';
    }
    else
    { 
      $this->listNavActionClass = 'wcm wcm_ui_tip';
    }
    
    return $this->listNavActionClass;
    
  }//end function getListNavActionClass */

}//end class LibGenfArchitecture_Wbf
