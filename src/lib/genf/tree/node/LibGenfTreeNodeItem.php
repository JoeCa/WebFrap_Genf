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
 *  <item name="roles" type="role_list" source="project_project" >
 *    
 *    <roles>
 *      <role name="admin" ></role>
 *      <role name="developer" ></role>
 *    </roles>
 *    
 *  </item>
 *  
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeItem
  extends LibGenfTreeNode
{

  /**
   * @var LibGenfNameMin
   */
  public $tabName = null;
  
  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management   = null;
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * 
   */
  public function getCatridgeClass()
  {
    return null;
  }//end public function getCatridgeClass */
  

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameItem( $this->node );
    
    $this->management = $this->builder->getManagement( $this->name->source );
    
    if( !$this->management )
    {
      $this->isInvalid = true;
      
      $this->error( "Invalid Item Node missing management: ".$this->name->source.' '.$this->debugData() );
      return;
    }

  }//end protected function loadChilds */
  
  /**
   * @return LibGenfNameItem
   */
  public function getName()
  {

    return $this->name;

  }//end public function getName */

  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getManagement()
  {

    return $this->management;

  }//end public function getManagement */
  
  /**
   * @return string
   */
  public function getRelation()
  {
    
    if( !isset( $this->node->relation ) )
      return 'dataset';
    else
      return strtolower( trim($this->node->relation) );
    
  }//end public function getRelation */
  
  /**
   * @return string
   */
  public function getAreaKey()
  {
    
  }//end public function getAreaKey */
  
  /**
   * Wenn nicht auf die Rowid sondern auf eine andere id auf dem Datensatz
   * verwiesen wird
   * 
   * @return string
   */
  public function getRefField()
  {
    
    if( isset( $this->node['ref_field'] ) )
      return trim($this->node['ref_field']);

  }//end public function getRefField */
  
////////////////////////////////////////////////////////////////////////////////
// Properties
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Eine Property aus dem ui element auslesen
   * 
   * @param string $key
   * @param string $attr
   * @param string $default Der Default Wert, welcher verwendet wird, wenn
   *   keine Property definiert wurde
   */
  public function getProperty( $key, $attr, $default = null )
  {

    if( isset($this->node->properties->{$key}[$attr] ) )
    {
      return trim( $this->node->properties->{$key}[$attr] );
    }

    return $default;

  }//end public function getProperty */

}//end class LibGenfTreeNodeItem

