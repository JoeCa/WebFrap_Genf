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
 *
 * Full example of an uiElement node
 * <uiElement
 *  type="selectbox"    Der UI Element Type
 *  src="some_entity"   Target Entity (optional)
 *  >
 *
 *  Text in der Textbox (optional)
 *  <box class="" >
 *    <append><text lang="de" >deutscher text</text></append>    // ovr / über je nach ausrichtung
 *    <prepend></prepend>  // nach / unter je nach ausrichtung
 *    <!-- macht hier nur sinn, wenn label oder element keinen tooltip haben -->
 *    <tooltip>
 *      <text lang="de">Tooltip des UI Elements</text>
 *    </tooltip>
 *  </box>
 *
 *  Text über/unter vor/nach dem Label (optional)
 *  <label class="" >
 *    <append></append>
 *    <prepend></prepend>
 *    <tooltip></tooltip>
 *  </label>
 *
 *  Text über/unter vor/nach dem UI Element (optional)
 *  <element class="" >   Klasse/n die dem UI Element angehängt werden
 *    <append></append>
 *    <prepend></prepend>
 *    <tooltip></tooltip>
 *  </element>
 *
 *  <!-- neben dem element ein bestimmtes menü positionieren -->
 *  <menu name="" /> (optional)
 *
 *  <readonly /> Sind die Daten im Element änderbar (optional)
 *  <hidden />   Wird die Box angezeigt oder versteckt (optional)
 *
 *
 *  größe des ui elements
 *  <size v_size="" h_size=""  />
 *
 *  positionierung des uielements innerhalb des formulars
 *  <layout priority="" v_pos="" h_pos="" target="" realtion="" />
 *
 *</uiElement>
 *
 *
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiElementField
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute, mainly for caching
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $type      = null;
  
  /**
   * @var string
   */
  public $elemName      = null;
  
  /**
   * @var string
   */
  public $field      = null;
  
  /**
   * @var boolean
   */
  public $readonly  = null;
  
  /**
   * @var boolean
   */
  public $disabled  = null;
  
  /**
   * @var boolean
   */
  public $required  = null;
  
  /**
   * @var boolean
   */
  public $isFilter  = null;
 
  /**
   * @var boolean
   */
  public $filter  = null;
  
  /**
   * @var boolean
   */
  public $hidden  = null;
  
  /**
   * @var array
   */
  public $menu  = null;
  
  /**
   * @var TArray
   */
  public $size       = null;
  
  /**
   * @var TArray
   */
  public $position   = null;

  /**
   * @var array
   */
  public $texts      = null;

  /**
   * @var array<LibGenfTreeNodeParam>
   */
  public $params     = null;
  
////////////////////////////////////////////////////////////////////////////////
// loader method
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @overwrite
   */
  protected function loadChilds()
  {
    
    if( isset( $this->node['type'] ) )
      $this->type = trim($this->node['type']);
      
    if( isset( $this->node['field'] ) )
      $this->field = trim($this->node['field']);
    
    if( isset( $this->node['name'] ) )
      $this->elemName = trim($this->node['name']);
      
    if( isset( $this->node->filter['field'] ) )
      $this->filter = trim($this->node->filter['field']);
      
    if( isset( $this->node->readonly ) )
    {
      if( !isset( $this->node->readonly['status'] ) )
      {
        $this->readonly = true;
      }
      else 
      {
        $this->readonly = trim($this->node->readonly['status']) == 'false' ? false:true;
      }
    }
    
    if( isset( $this->node->disabled ) )
    {
      if( !isset( $this->node->disabled['status'] ) )
      {
        $this->disabled = true;
      }
      else 
      {
        $this->disabled = trim($this->node->disabled['status']) == 'false' ? false:true;
      }
    }
    
    if( isset( $this->node->required ) )
    {
      if( !isset( $this->node->required['status'] ) )
      {
        $this->required = true;
      }
      else 
      {
        $this->required = trim($this->node->required['status']) == 'false' ? false:true;
      }
    }
    
    if( isset( $this->node->hidden ) )
    {
      if( !isset( $this->node->hidden['status'] ) )
      {
        $this->hidden = true;
      }
      else 
      {
        $this->hidden = trim($this->node->hidden['status']) == 'false' ? false:true;
      }
    }
    
    if( isset( $this->node->is_filter ) )
    {
      if( !isset( $this->node->is_filter['status'] ) )
      {
        $this->isFilter = true;
      }
      else 
      {
        $this->isFilter = trim($this->node->is_filter['status']) == 'false' ? false:true;
      }
    }
    
    if( isset( $this->node->menu['name'] ) )
      $this->menu = trim( $this->node->menu['name'] );
      
    $this->parseSize();
    $this->parsePosition();
    $this->parseTexts();
    $this->parseParams();
  
  }//end protected function loadChilds */
  

////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   * get the ui element type
   * @return string
   */
  public function type( $checkType = null )
  {

    if( !$checkType )
      return $this->type;

    return ( $this->type === $checkType );

  }//end public function type
  
  /**
   * get the ui element type
   * @return string
   */
  public function typeKey( $checkType = null )
  {

    if( !$checkType )
      return SParserString::subToCamelCase($this->type) ;

    return ( SParserString::subToCamelCase($this->type) === $checkType );

  }//end public function typeKey

  /**
   *
   * @return boolean
   */
  public function name()
  {
    return $this->node->elemName;
  }//end public function name */
  
  /**
   *
   * @return string
   */
  public function source()
  {
    return null;
  }//end public function source */

  /**
   *
   * @return string
   */
  public function field()
  {
    return $this->field;
  }//end public function field */

  /**
   *
   * @return boolean
   */
  public function readonly()
  {
    return $this->readonly;
  }//end public function readonly */
 

  /**
   *
   * @return boolean
   */
  public function disabled()
  {
    return $this->disabled ;
  }//end public function disabled */

  /**
   *
   * @return boolean
   */
  public function required()
  {
    return $this->required;
  }//end public function required */
  
  /**
   *
   * @return boolean
   */
  public function hidden()
  {
    return $this->hidden;
  }//end public function hidden */
  
  /**
   *
   * @return boolean
   */
  public function isFilter()
  {
    return $this->isFilter;
  }//end public function isFilter */
  
  /**
   * @return string
   */
  public function getFilter()
  {
    return $this->filter;
  }//end public function getFilter */

  /**
   * @return string
   */
  public function menu()
  {

   return $this->menu;

  }//end public function menu */

  /**
   * @param string $width
   * @param string $height
   * @return TArray
   */
  public function size( $width = null, $height = null )
  {

    if( $width )
    {
      if( !$this->size->width )
        $this->size->width = $width;
    }
    else
    {
      if( !$this->size->width )
        $this->size->width = 'medium';
    }

    if( $height )
    {
      if( !$this->size->height )
        $this->size->height = $height;
    }
    else
    {
      if( !$this->size->height )
        $this->size->height = '';
    }
    
    return $this->size;

  }//end public function size */

  /**
   * @return TArray
   */
  public function position()
  {

    return $this->position;

  }//end public function position */

  /**
   * @return TArray
   */
  public function texts(  )
  {

    return $this->texts;

  }//end public function texts */
  
  /**
   * @return array<LibGenfTreeNodeParam>
   */
  public function params(  )
  {

    return $this->params;

  }//end public function params */
  
  
////////////////////////////////////////////////////////////////////////////////
// parser methodes
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * @param string $width
   * @param string $height
   * @return TArray
   */
  public function parseSize( )
  {
    
    if( !isset( $this->node->size ) )
      return null;

    $size = new TArray();

    if( isset($this->node->size['width']) )
    {
      $size->width = trim($this->node->size['width']);
    }

    if( isset($this->node->size['height']) )
    {
      $size->height = trim($this->node->size['height']);
    }

    $this->size = $size;


  }//end public function getSize */

  /**
   * @return TArray
   */
  public function parsePosition()
  {

    if( !isset( $this->node->position ) )
      return null;

    $obj = new TArray();

    if( isset( $this->node->position['valign'] ) )
      $obj->valign = trim($this->node->position['valign']);
    else
      $obj->valign = 'middle';

    if( isset( $this->node->position['align'] ) )
      $obj->align = trim( $this->node->position['align'] );
    else
      $obj->align = 'auto';

    if( isset( $this->node->position['priority'] ) )
      $obj->priority = trim( $this->node->position['priority'] );
    else
      $obj->priority = '50';


    if( isset( $this->node->position['target'] ) )
    {

      // relation is only relevant if there is a target
      if( isset( $this->node->position['relation'] ) )
        $obj->relation  = trim( $this->node->position['relation'] );
      else
        $obj->relation  = 'below';

      $obj->target      = trim( $this->node->position['target'] );

    }

    $this->position = $obj;

  }//end public function parsePosition */

  /**
   * @param string $key box / label / element
   * @return TArray
   */
  public function parseTexts(  )
  {

    if( !isset( $this->node->texts ) )
      return null;

    $texts = array();

    foreach( $this->node->texts->text as $text )
    {
      $texts[trim($text['key'])] = trim($text);
    }
    
    if( $texts )
      $this->texts = $texts;

  }//end public function parseTexts */

  /**
   * Anfragen ob es Parameter für die Filter gibt
   * 
   * @return array<LibGenfTreeNodeParam>
   */
  public function parseParams(  )
  {
    
    if( !isset( $this->node->params ) )
      return null;
    
    $params = array();
      
    foreach( $this->node->params->param as $param )
    {
      $params[] = new LibGenfTreeNodeParam( $param );
    }
    
    $this->params = $params;

  }//end public function parseParams */

}//end class LibGenfTreeNodeUiElement

