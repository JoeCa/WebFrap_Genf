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
class LibGenfTreeNodeProcessEvent
  extends LibGenfTreeNode
{

  /**
   *
   * @var LibGenfNameMin
   */
  public $name;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibGenfNameMin
   */
  public function getName()
  {
    return $this->name;
  }//end public function getName */

  
  /**
   * @return array<LibGenfTreeNodeProcedure>
   */
  public function getProcedures()
  {
    
    if( !isset( $this->procedures->procedure ) )
      return array();
      
    $procedures = array();
      
    foreach( $this->procedures->procedure as $procedure )
    {
      
      $procedureType = SParserString::subToCamelCase( trim($procedure['type'] ) );
      $className = $this->builder->getNodeClass( 'Procedure'.$procedureType );
      
      if( !$className )
      {
        $this->builder->error( "Requested nonexisting Procedure{$procedureType} in ".$this->builder->dumpEnv() );
        continue;
      }
      
      $procedures[] = new  $className( $procedure );
    }

    return $procedures;
    
  }//end public function getProcedures */

}//end class LibGenfTreeNodeProcessEvent

