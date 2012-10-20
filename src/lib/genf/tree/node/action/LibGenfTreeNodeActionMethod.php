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
 * @example
 * <method name="render_page" >
 *  <interface type="dataset" />
 * </method>
 */
class LibGenfTreeNodeActionMethod
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {
    
    $this->name       = new LibGenfNameActionMethod( $this->node );

  }//end protected function loadChilds */

  /**
   * Der Interface Type definiert den Methodenkopf, also da Interface
   * der Methode
   * 
   * @return string
   */
  public function getInterfaceType()
  {
    
    return ucfirst( trim($this->node->interface['type']) );
  }//end public function getInterfaceType */

}//end class LibGenfTreeNodeActionMethod

