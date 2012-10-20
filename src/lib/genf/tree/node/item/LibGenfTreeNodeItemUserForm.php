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
class LibGenfTreeNodeItemUserForm
  extends LibGenfTreeNodeItem
{
////////////////////////////////////////////////////////////////////////////////
// Attributes 
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes 
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return string
   */
  public function getCatridgeClass()
  {
    return 'ItemUserForm';
  }//end public function getCatridgeClass */
  

  /**
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameItem( $this->node );
    
    if( isset( $this->node->access ) )
    {
      $this->access = new LibGenfTreeNodeElementAccess( $this->node->access );
    }
    
    $this->management = $this->builder->getManagement( trim($this->node['source']) );
    
    if( !$this->management )
    {
      $this->builder->error( "missing management for source ".trim($this->node['source']) );
    }

  }//end protected function loadChilds */
  
  /**
   * @return LibGenfTreeNodeElementAccess
   */
  public function getAccess()
  {

    return $this->access;

  }//end public function getAccess */
  
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
  public function getTargetManagement()
  {

    return $this->builder->getManagement( $this->name->source );

  }//end public function getTargetManagement */
  
  /**
   * @return string
   */
  public function getUserAttr()
  {

    return trim($this->node['user_attr']);

  }//end public function getUserAttr */
  
  /**
   * @return string
   */
  public function getRefAttr()
  {

    return trim($this->node['source_id']);

  }//end public function getRefAttr */
  
  /**
   * @return array
   */
  public function getFields( )
  {

    $raw = $this->extractFormLayoutFields( array(), $this->node->layout );

    if( is_null( $raw ) )
    {
      return null;
    }
    
    $nodeUser = new SimpleXMLElement('<field name="'.trim($this->node['user_attr']).'" ><ui_element type="hidden" /></field>');
    $raw[]    = $nodeUser;
    
    $nodeRef = new SimpleXMLElement('<field name="'.trim($this->node['source_id']).'" ><ui_element type="hidden" /></field>');
    $raw[]   = $nodeRef;

    $fieldClassName = $this->builder->getNodeClass( 'UiFormField' );
    $fields   = array();

    foreach( $raw as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }
    


    return $fields;

  }//end public function getFields */
  

  /**
   * @param string $context
   * @return array
   */
  public function getSaveFields(  )
  {

    $raw = null;
    $raw = $this->extractFormSaveFields( array(), $this->node->layout );

    if( is_null( $raw ) )
      return null;

    $fieldClassName = $this->builder->getNodeClass( 'UiFormField' );
    $fields   = array();
    
    $nodeUser = new SimpleXMLElement('<field name="'.trim($this->node['user_attr']).'" ><ui_element type="hidden" /></field>');
    $raw[]    = $nodeUser;
    
    $nodeRef = new SimpleXMLElement('<field name="'.trim($this->node['source_id']).'" ><ui_element type="hidden" /></field>');
    $raw[]   = $nodeRef;
    

    foreach( $raw as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getSaveFields */

  /**
   * @param boolean $autoAppendMeta
   */
  public function getCategories( $autoAppendMeta = false )
  {

    $use = null;

    if( isset($this->node->{$this->context}->use->category) )
    {
      $use = $this->node->{$this->context}->use;
    }
    else if( isset($this->node->crud->use->category) )
    {
      $use = $this->node->crud->use;
    }

    if( !$use )
    {
      if( !$this->fallback )
      {
        return null;
      }
      else
      {
        return $this->fallback->getCategories( $autoAppendMeta );
      }
    }

    $cats = array();

    foreach( $use->category as $category )
    {
      $cats[] = trim($category['name']);
    }

    if( $autoAppendMeta  )
    {
      if( !in_array( 'meta', $cats ) )
        $cats[] = 'meta';
    }

    return $cats;

  }//end public function getCategories */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractFormLayoutFields( $fields, $node )
  {

    if( !$children = $node->children() )
      return $fields;
      
    $breaker = array( 'user_form', 'item' );

    $meta = false;

    foreach( $children as $child )
    {

      if( 'field' == $child->getName() )
      {
        $fields[] = $child;
      }
      else if( in_array($child->getName(), $breaker)  )
      {
        // user_form inputs hier nicht auslesen
        continue;
      }
      else if( 'category' == $child->getName() )
      {

        if( 'meta' == trim( $child['name'] ) )
          $meta = true;

        $fields = $this->getFormCategoryFields( $fields, trim( $child['name'] ) );
      }
      else
      {
        $fields = $this->extractFormLayoutFields( $fields, $child );
      }
    }

    if( !$meta )
      $fields = $this->getFormCategoryFields( $fields, 'meta' );

    return $fields;

  }//end public function extractFormLayoutFields */


  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractFormSaveFields( $fields, $node )
  {

    if( !$children = $node->children() )
      return $fields;

    $meta = false;

    $fieldNames = array( 'field', 'value' );
    $breaker = array( 'user_form', 'item' );

    foreach( $children as $child )
    {

      if( in_array( $child->getName(), $fieldNames )  )
      {

        if( isset( $child['readonly'] ) )
        {
          continue;
        }

        $fields[] = $child;
      }
      else if( in_array($child->getName(), $breaker)  )
      {
        // user_form inputs hier nicht auslesen
        continue;
      }
      else if( 'category' == $child->getName() )
      {

        if( isset($child['readonly']) )
        {
          continue;
        }

        // prüfen ob die meta kategory angehängt wurde
        // diese wird in einigen fällen zum speichern benötigt
        if( 'meta' == trim( $child['name'] ) )
          $meta = true;

        $fields = $this->getFormCategoryFields( $fields, trim($child['name']) );
      }
      else
      {
        $fields = $this->extractFormSaveFields( $fields, $child );
      }
    }

    // wenn die metakategorie nicht vorhanden ist, wird sie automatisch
    // ans ende des formulars gepackt
    if( !$meta )
      $fields = $this->getFormCategoryFields( $fields, 'meta' );

    return $fields;

  }//end public function extractFormSaveFields */
  
  /**
   * @param array $fields
   * @param string $catname
   */
  public function getFormCategoryFields( $fields, $catname  )
  {
    
    if( !$this->management )
    {
      $this->management = $this->builder->getManagement( trim($this->node['source']) );
    }

    $catFields = $this->management->getCategoryFields( $catname );

    foreach( $catFields as $field )
    {
      $fields[] = $field;
    }

    return $fields;

  }//end public function getFormCategoryFields */
  

}//end class LibGenfTreeNodeItemUserForm

