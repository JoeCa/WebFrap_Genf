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
class LibGenfModelBdlDisplaynode
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  public $name          = null;


  // attr attributes

  /**
   * @var string
   */
  protected $attrField  = null;

  /**
   * @var string
   */
  protected $attrSrc    = null;
  
  /**
   * @var string
   */
  protected $attrRef    = null;

  /**
   * @var string
   */
  protected $attrAlias  = null;

  /**
   *
   * @var string
   */
  protected $attrType   = null;

  /**
   *
   * @var string
   */
  protected $attrIgnore   = null;

  /**
   *
   * @var string
   */
  protected $attrPriority   = null;

  /**
   *
   * @var string
   */
  protected $attrAction   = null;

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    if( is_bool($node) )
      return;

    // import attributes
    if( isset($node['field']) )
      $this->attrField = trim($node['field']);

    if( isset($node['src']) )
      $this->attrSrc = trim($node['src']);
      
    if( isset($node['ref']) )
      $this->attrRef = trim($node['ref']);

    if( isset($node['alias']) )
      $this->attrAlias = trim($node['alias']);

    if( isset($node['type']) )
      $this->attrType = trim($node['type']);

    if( isset($node['ignore']) )
      $this->attrIgnore = trim($node['ignore']);


    if( isset($node['priority']) )
      $this->attrPriority = trim($node['priority']);

    if( isset($node['action']) )
      $this->attrAction = trim($node['action']);

  }//end public function import */

  /**
   * @return string
   */
  public function parse()
  {

    // parse attributes
    $attributes = '';

    if( $this->attrField )
      $attributes .= ' field="'.$this->attrField.'" ';

    if( $this->attrSrc )
      $attributes .= ' src="'.$this->attrSrc.'" ';
      
    if( $this->attrRef )
      $attributes .= ' ref="'.$this->attrRef.'" ';

    if($this->attrAlias)
      $attributes .= ' alias="'.$this->attrAlias.'" ';

    if($this->attrType)
      $attributes .= ' type="'.$this->attrType.'" ';

    if($this->attrIgnore)
      $attributes .= ' ignore="'.$this->attrIgnore.'" ';

    if($this->attrPriority)
      $attributes .= ' priority="'.$this->attrPriority.'" ';

    if($this->attrAction)
      $attributes .= ' action="'.$this->attrAction.'" ';

    return '<'.$this->name.$attributes.'></'.$this->name.'>';

  }//end public function parse */


}//end class LibGenfModelBdlDisplaynode

