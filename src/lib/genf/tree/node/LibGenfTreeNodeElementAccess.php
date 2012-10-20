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
class LibGenfTreeNodeElementAccess
  extends LibGenfTreeNodeAccess
{

  /**
   * @return array<LinGenfTreeNodeAccessCheck>
   */
  public function getChecks( )
  {

    
    $checks     = array();

    if( isset( $this->node->checks->check ) )
    {
      foreach( $this->node->checks->check as $check )
      {
        
        $type = ucfirst( trim($check['type']) );
        
        $className  = $this->builder->getNodeClass( 'AccessCheck'.$type );
        
        if( !Webfrap::classLoadable($className) )
        {
          $this->builder->dumpError( "Requested nonexisting check type {$type}" );
          continue;
        }
        
        $checks[] = new $className( $check );
      }
    }

    return $checks;

  }//end public function getChecks */

}//end class LibGenfTreeNodeElementAccess

