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
 * 
 * @example Tag Beispiel
 * <procedure type="message" >
 * 
 *   <messages>
 *     <text lang="en" >Notified Assignment Creator</text>
 *   </messages>
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeProcedureWarning
  extends LibGenfTreeNodeProcedure
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameProcedure( $this->node );
    $this->loadConditions();

  }//end protected function loadChilds */

  
  /**
   * @param string $lang
   * 
   * @return string
   */
  public function getMessage( $lang = 'en' )
  {
    
    if( !isset( $this->node->messages ) )
      return null;
      
    return $this->i18nValue( $this->node->messages, $lang );
    
  }//end public function getMessage */


}//end class LibGenfTreeNodeProcedureWarning

