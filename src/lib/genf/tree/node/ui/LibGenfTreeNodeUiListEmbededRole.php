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
 * 
 * Referenz Tags in Listenelementen
 * 
 *  <reference name="project_tasks" target="project_tasks"  >
 *    <field tag="small" name="name" />
 *  </reference>
 * 
 */
class LibGenfTreeNodeUiListEmbededRole
  extends LibGenfTreeNode
{

  /**
   * @var array
   */
  public $fields = array();


  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name     = new LibGenfNameDefault( $this->node );


  }//end protected function loadChilds */
  
  /**
   * @param LibGenfEnvManagement $env
   * @return array
   */
  public function getFields( $env )
  {

    if( !$this->fields )
      $this->prepareFields( $env );

    return $this->fields;

  }//end public function getFields */


  /**
   * @return array
   */
  public function getRoles(  )
  {
    
    if( !isset( $this->node->display->role ) )
      return null;
      
    $roles = array();
      
    foreach( $this->node->display->role as $role )
    {
      $roles[] = trim($role['name']);
    }

    return $roles;

  }//end public function getRoles */


  /**
   * return the ui type for the list element
   * @return string
   */
  public function getUiType(  )
  {

    return 'List';

  }//end public function getUiType */

  /**
   *
   * @param LibGenfEnvReference $env
   */
  protected function prepareFields( $env )
  {

    

  }//end protected function prepareFields */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  protected  function extractFields( $fields, $node )
  {

    if(!$children = $node->children())
      return $fields;

    $listElem   = array('field','value','input');

    foreach( $children as $child )
    {

      $nodeName = strtolower($child->getName());

      if( in_array($nodeName, $listElem) )
      {
        $fields[] = $child;
      }
      else
      {
        $fields = $this->extractFields( $fields, $child );
      }

    }

    return $fields;

  }//end protected function extractFields */


}//end class LibGenfTreeNodeUiListEmbededRole

