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
class LibGenfTreeNodeActionAccess
  extends LibGenfTreeNodeAccess
{


  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    if( isset( $this->node->level ) )
    {
      if( isset( $this->node->level['user'] ) )
        $this->userLevel = trim($this->node->level['user']);
  
      if( isset( $this->node->level['min'] ) )
        $this->accessLevel = trim( $this->node->level['min'] );
        
      if( isset( $this->node->level['max'] ) )
        $this->maxLevel = trim( $this->node->level['max'] );
    }
    else 
    {
      if( isset( $this->node['user_level'] ) )
        $this->userLevel = trim($this->node['user_level']);
  
      if( isset( $this->node['access_level'] ) )
        $this->accessLevel = trim( $this->node['access_level'] );
        
      if( isset( $this->node['max_level'] ) )
        $this->maxLevel = trim( $this->node['max_level'] );
    }
    
  }//end protected function loadChilds */
  
  
  /**
   * @return array<LibGenfTreeNodeAccessCheck>
   */
  public function getChecks( )
  {

    $checks     = array();

    if( isset( $this->node->checks->check ) )
    {
      
      foreach( $this->node->checks->check as $check )
      {
        
        $typeKey   = SParserString::subToCamelCase( trim($check['type']) );
        $className = $this->builder->getNodeClass( 'AccessCheck_'.$typeKey );
        
        if( Webfrap::classLoadable( $className ) )
        {
          $checks[] = new $className( $check );
        }
        else 
        {
          $this->builder->dumpError( "Requested invalid Check type ".$typeKey );
        }
      }
    }

    return $checks;

  }//end public function getChecks */
 

}//end class LibGenfTreeNodeActionAccess

