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
class LibGenfTreeNodeAction_Plain
  extends LibGenfTreeNodeAction
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameAction_Plain( $this->node );
    
    if( isset( $this->node->methodes->method ) )
    {
      foreach( $this->node->methodes->method as $method )
      {
        $this->methodes[] = new LibGenfTreeNodeActionMethod( $method );
      }
    }
    
    
  }//end protected function loadChilds */
  
 

}//end class LibGenfTreeNodeAction_Dataset

