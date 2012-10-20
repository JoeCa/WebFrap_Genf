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
class LibGenfTreeNodeLayoutReference
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTreeNodeReference
   */
  public $ref = null;

  /**
   *
   */
  public function getUiElement()
  {

    if( isset( $this->node->ui->element ) )
      return trim( $this->node->ui->element['type'] );

    if( $this->ref->relation(Bdl::MANY_TO_MANY) )
    {
      
      $refMgmt = $this->ref->connectionManagement();
  
      if( !$refMgmt )
      {
        $this->builder->dumpError( "Missing Ref Connection" );
        return 'table';
      }
      
      return $refMgmt->concept('tree')?'treetable':'table';
    }
    else
    {
      
      $refMgmt = $this->ref->targetManagement();
  
      if( !$refMgmt )
      {
        $this->builder->dumpError( "Missing Ref Target" );
        return 'table';
      }
      
      return $refMgmt->concept('tree')?'treetable':'table';
    }


  }//end public function getUiElement */



}//end class LibGenfTreeNodeLayoutReference

