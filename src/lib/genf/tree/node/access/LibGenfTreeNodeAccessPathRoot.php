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
class LibGenfTreeNodeAccessPathRoot
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return [LibGenfTreeNodeAccessCheck]
   */
  public function getChecks( )
  {

    
    $checks     = array();

    if( isset( $this->node->checks->check ) )
    {
      foreach( $this->node->checks->check as $check )
      {
        
        $className  = $this->builder->getNodeClass
        ( 
          'AccessCheck_'.SParserString::subToCamelCase( trim($check['type']) ) 
        );
        
        $checks[] = new $className( $check );
      }
    }

    return $checks;

  }//end public function getChecks */
  
  public function getLevelToSet()
  {
    
  }//end public function getLevelToSet */
  
  public function getRolesToSet()
  {
    
  }

  public function getReferences()
  {
    
  }
  
}//end class LibGenfTreeNodeAccess

