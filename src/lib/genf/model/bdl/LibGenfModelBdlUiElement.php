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
class LibGenfModelBdlUiElement
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // attr attributes
  protected $attrType       = null;
  protected $attrMode       = null;
  protected $attrVariant    = null;
  protected $attrSrc        = null;
  protected $attrReadonly   = null;
  protected $attrDisabled   = null;
  protected $attrHidden     = null;
  protected $attrMenu       = null;

  // attr nodes
  protected $tagSize      = null;
  protected $tagLayout    = null;
  protected $tagPosition  = null;


////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['type']) )
      $this->attrType   = trim($node['type']);

    if( isset($node['src']) )
      $this->attrSrc  = trim($node['src']);
      
    if( isset($node['variant']) )
      $this->attrVariant  = trim($node['variant']);
      
    if( isset($node['mode']) )
      $this->attrMode  = trim($node['mode']);

    if( isset($node->readonly) )
      $this->attrReadonly   = true;

    if( isset($node->disabled) )
      $this->attrDisabled   = true;

    if( isset($node->hidden) )
      $this->attrHidden     = true;


    // import nodes
    if( isset( $node->size ) )
    {
      $this->size   = $node->size;
    }
    if( isset( $node->layout ) )
    {
      $this->layout = $node->layout;
    }
    if( isset( $node->position ) )
    {
      $this->position = $node->position;
    }

  }//end public function import */

  /**
   * @return string
   */
  public function parse()
  {

    // parse attributes
    $type   = '';
    if( $this->attrType )
      $type = ' type="'.$this->attrType.'" ';

    $src    = '';
    if( $this->attrSrc )
      $src  = ' src="'.$this->attrSrc.'" ';
      
    $variant    = '';
    if( $this->attrVariant )
      $variant  = ' variant="'.$this->attrVariant.'" ';
      
    $mode    = '';
    if( $this->attrMode )
      $mode  = ' mode="'.$this->attrMode.'" ';

    $tags = '';

    if( $this->attrReadonly )
      $tags .= '<readonly />';

    if( $this->attrDisabled )
      $tags .= '<disabled />';

    if( $this->attrHidden )
      $tags .= '<hidden />';

    // parse subnodes
    if($this->tagSize)
      $tags .= $this->tagSize->parse();

    if($this->tagLayout)
      $tags .= $this->tagLayout->parse();

    if($this->tagPosition)
      $tags .= $this->tagPosition->parse();

    $xml = <<<XMLS
      <uiElement {$type} {$src} {$variant} {$mode} >
        {$tags}
      </uiElement>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlUiElement

