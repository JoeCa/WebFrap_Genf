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
class LibGenfTreeNodeUiReference
  extends LibGenfTreeNodeUi
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @var LibGenfTreeNodeReference
   */
  public $reference = null;




/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   * @return array
   */
  public function getFormFields( $context, $category = null )
  {

    $raw = null;

    if( isset( $this->node->form->$context->layout ) )
    {
      $raw = $this->extractFormLayoutFields(array(), $this->node->form->$context->layout );
    }
    elseif( isset( $this->node->form->crud->layout ) )
    {
      $raw = $this->extractFormLayoutFields(array(), $this->node->form->crud->layout );
      //Debug ::console('form crud layout',$raw);
    }

    if( is_null($raw) )
      return  null;

    $fieldClassName = $this->builder->getNodeClass( 'UiFormField' );
    $fields   = array();

    foreach( $raw as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getFormFields */

  /**
   * @param array $fields
   * @param string $catname
   * @return array
   */
  public function getFormCategoryFields( $fields, $catname  )
  {

    if( $this->reference->relation( Bdl::MANY_TO_MANY ) )
    {
      $mgmt = $this->reference->connectionManagement();

      $catFields = $mgmt->getCategoryFields( $catname );
      foreach( $catFields as $field )
      {
        $fields[] = $field;
      }
    }

    $mgmt = $this->reference->targetManagement();

    $catFields = $mgmt->getCategoryFields( $catname );
    foreach( $catFields as $field )
    {
      $fields[] = $field;
    }

    return $fields;

  }//end public function getFormCategoryFields

}//end class LibGenfTreeNodeUiReference

