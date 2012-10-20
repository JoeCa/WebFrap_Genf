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
class LibGenfTreeNodeUiElementMerger
{
////////////////////////////////////////////////////////////////////////////////
// attribute, mainly for caching
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfTreeNodeUiElement
   */
  public $attrUi = null;
  
  /**
   * @var LibGenfTreeNodeUiElementField
   */
  public $fieldUi = null;
  
  /**
   * @var string
   */
  public $type = null;
  
////////////////////////////////////////////////////////////////////////////////
// constructor
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfTreeNodeUiElement $attrUi
   * @param LibGenfTreeNodeUiElementField $fieldUi
   */
  public function __construct( $attrUi, $fieldUi )
  {
    
    $this->attrUi   = $attrUi;
    $this->fieldUi  = $fieldUi;
    
    if( $this->fieldUi && $this->fieldUi->type )
      $this->type = $this->fieldUi->type(  );
    else 
      $this->type = $this->attrUi->type(  );

    
  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   * get the ui element type
   * @return string
   */
  public function type( $checkType = null )
  {

    if( $this->fieldUi && $this->fieldUi->type )
      return $this->fieldUi->type( $checkType );
      
    return $this->attrUi->type( $checkType );

  }//end public function type */
  
  /**
   * @return string
   */
  public function name()
  {
    
    if( $this->fieldUi && $this->fieldUi->elemName )
      return $this->fieldUi->elemName;
      
    return $this->attrUi->name( );
    
  }//end public function name */

  /**
   *
   * @return string
   */
  public function source()
  {
    
    return $this->attrUi->source( );
    
  }//end public function source */

  /**
   *
   * @return string
   */
  public function field()
  {
    
    return $this->attrUi->field( );
      
  }//end public function field */

  /**
   *
   * @return boolean
   */
  public function readonly()
  {
    
    if( $this->fieldUi && $this->fieldUi->readonly )
      return $this->fieldUi->readonly( );
      
    return $this->attrUi->readonly( );
    
  }//end public function readonly */

  /**
   *
   * @return boolean
   */
  public function disabled()
  {
    
    if( $this->fieldUi && $this->fieldUi->disabled )
      return $this->fieldUi->disabled( );
      
    return $this->attrUi->disabled( );
    
  }//end public function disabled */

  /**
   *
   * @return boolean
   */
  public function required()
  {
    
    if( $this->fieldUi && $this->fieldUi->required )
      return $this->fieldUi->required( );
      
    return $this->attrUi->required( );
    
  }//end public function required */
  
  /**
   *
   * @return boolean
   */
  public function hidden()
  {
    
    if( $this->fieldUi && $this->fieldUi->hidden )
      return $this->fieldUi->hidden( );
      
    return $this->attrUi->hidden( );
    
  }//end public function hidden */
  
  /**
   *
   * @return boolean
   */
  public function isFilter()
  {
    
    if( $this->fieldUi && $this->fieldUi->isFilter )
      return $this->fieldUi->isFilter( );
      
    return $this->attrUi->isFilter( );
    
  }//end public function isFilter */
  
  /**
   * @return string
   */
  public function getFilter()
  {
    
    if( $this->fieldUi && $this->fieldUi->filter )
      return $this->fieldUi->getFilter( );
      
    return $this->attrUi->getFilter( );
    
  }//end public function getFilter */

  /**
   * @return string
   */
  public function menu()
  {

    if( $this->fieldUi && $this->fieldUi->menu )
      return $this->fieldUi->menu( );
      
    return $this->attrUi->menu( );

  }//end public function menu */

  /**
   * @param string $width
   * @param string $height
   * @return TArray
   */
  public function size( $width = null, $height = null )
  {
    
    if( $this->fieldUi && $this->fieldUi->size )
      return $this->fieldUi->size( $width, $height );
      
    return $this->attrUi->size( $width, $height );

  }//end public function size */

  /**
   * @return TArray
   */
  public function position()
  {

    if( $this->fieldUi && $this->fieldUi->position )
      return $this->fieldUi->position( );
      
    return $this->attrUi->position( );

  }//end public function position */

  /**
   * @return TArray
   */
  public function texts(  )
  {

    if( $this->fieldUi && $this->fieldUi->texts )
      return $this->fieldUi->texts( );
      
    return $this->attrUi->texts( );

  }//end public function texts */
  
  /**
   * @return array<LibGenfTreeNodeParam>
   */
  public function params(  )
  {

    if( $this->fieldUi && $this->fieldUi->params )
      return $this->fieldUi->params( );
      
    return $this->attrUi->params( );

  }//end public function params */


}//end class LibGenfTreeNodeUiElementMerger

