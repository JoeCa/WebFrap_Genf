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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiGroup
  extends LibGenfTreeNode
{

  /**
   *
   * @param $key
   * @param $context
   * @return array
   *
   */
  public function required( )
  {

    if( !isset( $this->node['required'] ) )
      return null;
    else
      return ('true' == trim($this->node['required']));


  }//end public function required */


  /**
   *  @return string
   */
  public function field( )
  {
    // ok field is required, add some error handling here
    return isset($this->node['field'])?trim($this->node['field']):null;

  }//end public function field */

  /**
   *  @return LibGenfNameDefault
   */
  public function fieldName( $management )
  {

    if(isset($this->node['src']))
    {

      if(!$mgmt = $this->builder->getManagement( trim($this->node['src'])) )
      {
        $this->warn( 'There is no Management for the source '.$this->node['src'] );
        return null;
      }

      if( !$attr = $mgmt->entity->getAttribute( trim($this->node['field']) ) )
      {
        $this->warn( 'There is no field '.$this->node['field'].' in entity '.$mgmt->entity->name->name );
        return null;
      }

      return $attr->name;

    }
    else
    {

      if( !$attr = $management->entity->getAttribute( trim($this->node['field']) ) )
      {
        $this->warn( 'There is no field '.$this->node['field'].' in entity '.$management->entity->name->name );
        return null;
      }

      return $attr->name;

    }//end else

  }//end public function fieldName */


  /**
   *
   * @param LibGenfTreeNodeManagement $management
   * @return LibGenfTreeNodeManagement
   */
  public function fieldSrc( $management )
  {

    if(isset($this->node['src']))
    {

      if(!$mgmt = $this->builder->getManagement( trim($this->node['src'])) )
      {
        $this->warn( 'There is no Management for the source '.$this->node['src'] );
        return null;
      }

      return $mgmt;

    }

    return $management;

  }//end public function fieldSrc */

  /**
   *
   * @param LibGenfTreeNodeManagement $management
   * @return LibGenfTreeNodeManagement
   */
  public function fieldTarget( $management )
  {

    if(isset($this->node['src']))
    {

      if(!$mgmt = $this->builder->getManagement( trim($this->node['src'])) )
      {
        $this->warn( 'There is no Management for the source '.$this->node['src'] );
        return null;
      }

      if( !$attr = $mgmt->entity->getAttribute( trim($this->node['field']) ) )
      {
        $this->warn( 'There is no field '.$this->node['field'].' in entity '.$mgmt->entity->name->name );
        return null;
      }

      if( !$targetMgmt = $attr->targetManagement( ) )
      {
        $this->warn( 'Field '.$this->node['field'].' in entity '.$targetMgmt->entity->name->name.' has no valid target' );
        return null;
      }

      return $targetMgmt;

    }
    else
    {

      if( !$attr = $management->entity->getAttribute( trim($this->node['field']) ) )
      {
        $this->warn( 'There is no field '.$this->node['field'].' in entity '.$management->entity->name->name );
        return null;
      }

      if( !$targetMgmt = $attr->targetManagement( ) )
      {
        $this->warn( 'Field '.$this->node['field'].' in entity '.$management->entity->name->name.' has no valid target' );
        return null;
      }

      return $targetMgmt;

    }//end else

  }//end public function fieldTarget */



}//end class LibGenfTreeNodeUiGroup

