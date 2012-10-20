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
class LibGenfTreeNodeConceptIndex
  extends LibGenfTreeNodeConcept
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getNameFields()
  {
  
    if( !isset( $this->node->name->field ) )
      return null;
      
    $fields = array();
    
    foreach( $this->node->name->field as $field )
    {
      $fields[] = trim( $field['name'] );
    }
     
    return $fields;
  
  }//end public function getNameFields */
  
  /**
   * @return string
   */
  public function getTitleFields()
  {
    
    if( !isset( $this->node->title->field ) )
      return null;
      
    $fields = array();
    
    foreach( $this->node->title->field as $field )
    {
      $fields[] = trim( $field['name'] );
    }
     
    return $fields;
    
  }//end public function getTitleFields */
  
  /**
   * @return string
   */
  public function getKeyFields()
  {
  
    if( !isset( $this->node->access_key->field ) )
      return null;
      
    $fields = array();
    
    foreach( $this->node->access_key->field as $field )
    {
      $fields[] = trim( $field['name'] );
    }
     
    return $fields;
  
  }//end public function getKeyFields */
  
  /**
   * @return string
   */
  public function getDescriptionFields()
  {

    if( !isset( $this->node->description->field ) )
      return array();
    
    $fields = array();
    
    foreach( $this->node->description->field as $field )
    {
      $fields[] = trim($field['name']);
    }
    
    return $fields;
      
  }//end public function getDescriptionFields */
  
  /**
   * @return array
   */
  public function getIndexFields()
  {

    if( !isset( $this->node->index->field ) )
      return array();
    
    $fields = array();
    
    foreach( $this->node->index->field as $field )
    {
      $fields[] = trim($field['name']);
    }
    
    return $fields;
      
  }//end public function getIndexFields */
  
  /**
   * Check ob die die indizierten Werte in diesen Tabellen im öffentlichen
   * Suchindex landen dürfen oder nur nach freigabe
   * @return boolean
   */
  public function isPrivate()
  {

    if( isset( $this->node->private ) )
      return true;

    return false;
      
  }//end public function isPrivate */
  
  
}//end class LibGenfTreeNodeConceptIndex

