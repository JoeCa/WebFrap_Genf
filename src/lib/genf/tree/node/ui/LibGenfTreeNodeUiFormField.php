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
class LibGenfTreeNodeUiFormField
  extends LibGenfTreeNode
{


  /**
   * @return string
   */
  public function getField( )
  {

    return ( $this->node instanceof TContextAttribute )
      ? $this->node
      : null;

  }//end public function getField */

  /**
   * @return string
   */
  public function fieldName( )
  {
    // if not exists, that's an error
    if(!isset( $this->node['name'] ) )
      return null;

    return trim($this->node['name']);

  }//end public function fieldName */

  /**
   * @return string
   */
  public function displayField()
  {
    return null;
  }//end public function displayField */

  /**
   * @deprecated
   * @return string
   */
  public function src( )
  {

    // optional
    if(!isset( $this->node['src'] ) )
      return null;

    return trim($this->node['src']);

  }//public function src */
  
  /**
   * @return string
   */
  public function reference( )
  {

    if( isset( $this->node['ref'] ) )
      return $this->node['ref'];

    if(!isset( $this->node['src'] ) )
      return null;

    return trim($this->node['src']);

  }//public function reference */

  /**
   * @return string
   */
  public function refType( )
  {

    // optional
    if(!isset( $this->node['ref_type'] ) )
      return 'connection';

    return trim($this->node['ref_type']);

  }//public function refType */

  /**
   * @return string
   */
  public function action( )
  {
    // optional
    if(!isset( $this->node['action'] ) )
      return null;

    return trim($this->node['action']);

  }//public function action */
  
  /**
   * @return string
   */
  public function uiElement( )
  {
    // optional
    if(!isset( $this->node['ui_element'] ) )
      return null;

    return trim( $this->node['ui_element'] );

  }//public function uiElement */
  
  /**
   * @return LibGenfTreeNodeAttributeAccess
   */
  public function getAccess( )
  {
    // optional
    if( !isset( $this->node->access ) )
      return null;

    return new LibGenfTreeNodeAttributeAccess( $this->node->access );

  }//public function getAccess */
  
  /**
   * @return LibGenfTreeNodeUiElementField
   */
  public function getUiElement( )
  {
    // optional
    if( !isset( $this->node->ui_element ) )
      return null;

    return new LibGenfTreeNodeUiElementField( $this->node->ui_element );

  }//public function getUiElement */
  
  /**
   * @return boolean
   */
  public function isReadOnly( )
  {
    
    if( $this->node instanceof TContextAttribute )
      return $this->node->readOnly;
    
    // optional
    if( !isset( $this->node->ui_element->readonly ) )
      return false;
      
    if( isset( $this->node->ui_element->readonly['status'] ) )
    {
      return trim($this->node->ui_element->readonly['status']) == 'false' ? false:true;
    }
    else 
    {
      return true;
    }

  }//public function isReadOnly */
  
  /**
   * @return boolean
   */
  public function isRequired( )
  {
    
    if( $this->node instanceof TContextAttribute )
      return $this->node->required;
      
    //if( $this )

    // optional
    if( !isset( $this->node->ui_element->required ) )
      return false;
    
    if( isset( $this->node->ui_element->required['status'] ) )
    {
      return trim($this->node->ui_element->required['status']) == 'false' ? false:true;
    }
    else 
    {
      return true;
    }

  }//public function isRequired */
  
  /**
   * @return boolean
   */
  public function isHidden( )
  {
    
    if( $this->node instanceof TContextAttribute )
      return $this->node->hidden;
    
    // optional
    if( !isset( $this->node->ui_element->hidden ) )
      return false;
    
    if( isset( $this->node->ui_element->hidden['status'] ) )
    {
      return trim($this->node->ui_element->hidden['status']) == 'false' ? false:true;
    }
    else 
    {
      return true;
    }

  }//public function isHidden */
  
  /**
   * @return boolean
   */
  public function isDisabled( )
  {
    
    if( $this->node instanceof TContextAttribute )
      return $this->node->disabled;
    
    // optional
    if( !isset( $this->node->ui_element->disabled ) )
      return false;
    
    if( isset( $this->node->ui_element->disabled['status'] ) )
    {
      return trim($this->node->ui_element->disabled['status']) == 'false' ? false:true;
    }
    else 
    {
      return true;
    }

  }//public function isDisabled */
  
  /**
   * @return string
   */
  public function defaultValue( )
  {

    if( !isset( $this->node->default ) )
      return null;

    return trim( $this->node->default );

  }//public function defaultValue */
  
  /**
   * @return string
   */
  public function defaultValueTarget( )
  {

    if( !isset( $this->node->default['target'] ) )
      return null;

    return trim( $this->node->default['target'] );

  }//public function defaultValueTarget */
  
  /**
   * @return string
   */
  public function defaultValueTargetAttr( )
  {

    if( !isset( $this->node->default['attr'] ) )
      return null;

    return trim( $this->node->default['attr'] );

  }//public function defaultValueTargetAttr */
  
  /**
   * @return boolean
   */
  public function getTexts( $lang = 'en' )
  {
    
    if( $this->node instanceof TContextAttribute )
      return $this->node->getTexts( $lang );
    
    // optional
    if( !isset( $this->node->texts->text ) )
      return null;
  
    ///TODO finish me

  }//public function getTexts */

  /**
   * @param string $langKey
   * @return string
   */
  public function label( $langKey = 'en' )
  {
    
    if( isset($this->labels[$langKey]) )
      return $this->labels[$langKey];

    // check if there is a label
    if( isset( $this->node->label->text ) )
    {

      // check if we find a lable for the given language
      foreach( $this->node->label->text as $text )
      {
        if( trim($text['lang']) ==  $langKey && '' != trim($text) )
        {
          $this->labels[$langKey] = trim($text);
          return $this->labels[$langKey];
        }
      }

      // fallback auf englisch wenn der angefragte key nicht vorhanden war
      if( 'en' !== $langKey )
      {
        foreach( $this->node->label->text as $text )
        {
          if( 'en' === trim($text['lang']) && '' !== trim($text)  )
          {
            $this->labels[$langKey] = trim($text);
            return $this->labels[$langKey];
          }
        }
      }

      // if not yet found return the first text, asuming this textnode has
      // the highest priority, if not fix your model and RTFM
      foreach( $this->node->label->text as $text )
      {
        // ends in the first element, did not found an easier way to get the
        // first node in simple xml... just fix it if you know better
        if( '' != trim($text) )
        {
          $this->labels[$langKey] = trim($text);
          return $this->labels[$langKey];
        }
      }


    }
    else if( isset( $this->node->label )  && '' != trim($this->node->label) )
    {
      // if no text but a label is given, we asume that the lable just contains
      // the text, if not... RTFM
      $this->labels[$langKey] = trim( $this->node->label );
      return $this->labels[$langKey];
    }

    return null;

  }//end public function getLabel */
  


}//end class LibGenfTreeNodeUiFormField

