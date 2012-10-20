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
class LibGenfTreeNodeUiElement
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute, mainly for caching
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTreeNodeAttribute
   */
  protected $attribute  = null;

  /**
   * @var TArray
   */
  protected $position     = null;

  /**
   *
   * @var TArray
   */
  protected $size       = null;

  /**
   *
   * @var array
   */
  protected $texts      = array();


////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   * get the ui element type
   * @return string
   */
  public function type( $checkType = null )
  {

    $type = isset($this->node['type'])
      ? trim($this->node['type'])
      : 'guess';

    if( !$checkType )
      return $type;

    return ( $type == $checkType );

  }//end public function type
  
  /**
   * @return string
   */
  public function getVariant( )
  {

    return isset( $this->node['variant'] )
      ? trim( $this->node['variant'] )
      : null;

  }//end public function getVariant */
  
  /**
   * @return string
   */
  public function name( )
  {

    return isset( $this->node['name'] )
      ? trim( $this->node['name'] )
      : null;

  }//end public function name */

  /**
   *
   * @return string
   */
  public function source()
  {
    return isset( $this->node['src'] )
      ? trim( $this->node['src'] )
      : null;
  }//end public function source */

  /**
   *
   * @return string
   */
  public function field()
  {
    return isset( $this->node['field'] )
      ? trim( $this->node['field'] )
      : null;
  }//end public function field */

  /**
   *
   * @return boolean
   */
  public function readonly()
  {
    return isset( $this->node->readonly );
  }//end public function readonly */

  /**
   *
   * @return boolean
   */
  public function disabled()
  {
    return isset( $this->node->disabled );
  }//end public function disabled */

  /**
   *
   * @return boolean
   */
  public function required()
  {
    return isset( $this->node->required );
  }//end public function required */
  
  /**
   *
   * @return boolean
   */
  public function hidden()
  {
    return isset( $this->node->hidden );
  }//end public function hidden */
  
  /**
   * Den Modus für das UIElement erfragen
   * Wird z.B beim WYSIWYG Editor benötigt um die verschiedenen Schemas 
   * zu laden
   * 
   * @return string
   */
  public function mode()
  {
    return isset( $this->node['mode'] )
      ? trim( $this->node['mode'] )
      : null;
      
  }//end public function mode */

  /**
   *
   * @return boolean
   */
  public function isFilter()
  {
    return isset( $this->node->is_filter );
  }//end public function isFilter */
  
  /**
   *
   * @return boolean
   */
  public function getFilter()
  {
    
    if( isset( $this->node->filter['field'] ) )
      return trim( $this->node->filter['field'] );
    else
      return null;
      
  }//end public function getFilter */
  
  /**
   * @return string
   */
  public function menu()
  {

    if( !isset( $this->node->menu ) || !isset( $this->node->menu['name'] ) )
      return null;
    else
      return trim( $this->node->menu['name'] );

  }//end public function menu */

  /**
   * @param string $width
   * @param string $height
   * @return TArray
   */
  public function size( $width = null, $height = null )
  {
    if( $this->size )
      return $this->size;

    $size = new TArray();

    if( isset($this->node->size['width']) )
    {
      $size->width = trim($this->node->size['width']);
    }
    elseif( $width )
    {
      $size->width = $width;
    }
    else
    {
      $size->width = 'medium';
    }

    if( isset($this->node->size['height']) )
    {
      $size->height = trim($this->node->size['height']);
    }
    elseif( $height )
    {
      $size->height = $height;
    }
    else
    {
      $size->height = '';
    }

    $this->size = $size;

    return $size;

  }//end public function size */

  /**
   * @return TArray
   */
  public function position()
  {

    if( $this->position )
      return $this->position;

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

    return $this->position;

  }//end public function position */

  /**
   * @param string $key box / label / element
   * @return TArray
   */
  public function texts(  )
  {

    if( !isset( $this->node->texts->text ) )
    {
      return array();
    }

    $texts = array();

    foreach( $this->node->texts->text as $text )
    {
      $texts[trim($text['key'])] = trim($text);
    }

    return $texts;

  }//end public function texts */
  
  
  /**
   * @param string $key box / label / element
   * @return array
   */
  public function getDataSource( $asKey = true  )
  {

    if( !isset( $this->node->data['src'] ) )
    {
      return null;
    }
    
    if( $asKey )
    {
      return SParserString::subToCamelCase( trim( $this->node->data['src'] ) );
    }
    else 
    {
      return trim( $this->node->data['src'] );
    }

  }//end public function getDataSource */

  /**
   * @param string $key box / label / element
   * @return array
   */
  public function getData(  )
  {

    if( !isset( $this->node->data->value ) )
    {
      return array();
    }

    $datas = array();

    foreach( $this->node->data->value as $value )
    {
      
      $tmpName = new LibGenfNameMin($value);
      
      $datas[trim($value['key'])] = $tmpName->label;
    }

    return $datas;

  }//end public function getData */
  
  /**
   * @return array<LibGenfTreeNodeParam>
   */
  public function params(  )
  {

    if( !isset( $this->node->params ) )
      return null;
    
    $params = array();
      
    foreach( $this->node->params->param as $param )
    {
      $params[] = new LibGenfTreeNodeParam( $param );
    }
    
    return $params;

  }//end public function params */

////////////////////////////////////////////////////////////////////////////////
// implement parent nodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @overwrite should be implemented if needed
   * @return void
   */
  protected function prepareNode( $params = array() )
  {

    // backlink to the attribute
    $this->attribute = $params[0];

  }//end protected function prepareNode */


}//end class LibGenfTreeNodeUiElement

