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
class LibGenfModelBdlAttribute
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // entity
  public $entity        = null;

  // attr attributes
  /**
   * @var string
   */
  protected $attrName      = null;

  /**
   * @var string
   */
  protected $attrTarget    = null;

  /**
   * @var string
   */
  protected $attrTargetField    = null;

  /**
   * @var string
   */
  protected $attrTargetAlias = null;

  /**
   * @var string
   */
  protected $attrSize      = null;

  /**
   * @var string
   */
  protected $attrType      = null;

  /**
   * @var string
   */
  protected $attrRequired  = null;

  /**
   * @var string
   */
  protected $attrValidator = null;

  /**
   * @var string
   */
  protected $attrMinSize   = null;

  /**
   * @var string
   */
  protected $attrMaxSize   = null;

  /**
   * @var string
   */
  protected $attrStepSize   = null;

  /**
   * @var string
   */
  protected $attrIndex   = null;

  // node attributes

  /**
   * @var LibGenfModelBdlDisplay
   */
  protected $tagDisplay       = null;

  /**
   * @var LibGenfModelBdlSearch
   */
  protected $tagSearch        = null;

  /**
   * @var LibGenfModelBdlUiElement
   */
  protected $tagUiElement     = null;

  /**
   * @var LibGenfModelBdlLabel
   */
  protected $tagLabel         = null;

  /**
   * @var LibGenfModelBdlDescription
   */
  protected $tagDescription   = null;

  /**
   * @var LibGenfModelBdlConcepts
   */
  protected $tagConcepts      = null;

  /**
   * @var LibGenfModelBdlCategories
   */
  protected $tagCategories    = null;

  /**
   * @var LibGenfModelBdlUnique
   */
  protected $tagUnique        = null;

  /**
   * @var LibGenfModelBdlAccess
   */
  protected $tagAccess        = null;

  /**
   * @var LibGenfModelBdlDefault
   */
  protected $tagDefault       = null;

  /**
   * @var LibGenfModelBdlSemantic
   */
  protected $tagSemantic      = null;

  /**
   * @var LibGenfModelBdlProcess
   */
  protected $tagProcess      = null;

////////////////////////////////////////////////////////////////////////////////
// parse
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['name']) )
      $this->attrName = trim($node['name']);

    if( isset($node['target']) )
      $this->attrTarget = trim($node['target']);

    if( isset($node['target_field']) )
      $this->attrTargetField = trim($node['target_field']);

    if( isset($node['target_alias']) )
      $this->attrTargetAlias = trim($node['target_alias']);

    if( isset($node['size']) )
      $this->attrSize = trim($node['size']);

    if( isset($node['type']) )
      $this->attrType = trim($node['type']);

    if( isset($node['required']) )
      $this->attrRequired = trim($node['required']);

    if( isset($node['validator']) )
      $this->attrValidator = trim($node['validator']);

    if( isset($node['minSize']) )
      $this->attrMinSize = trim($node['minSize']);

    if( isset($node['maxSize']) )
      $this->attrMaxSize = trim($node['maxSize']);

    if( isset($node['step_size']) )
      $this->attrStepSize = trim($node['step_size']);

    if( isset($node['index']) )
      $this->attrIndex = trim($node['index']);

    // import nodes
    if( isset( $node->display ) )
    {
      $this->display = $node->display;
    }

    if( isset( $node->uiElement ) )
    {
      $this->uiElement = $node->uiElement;
    }

    if( isset( $node->search ) )
    {
      $this->search = $node->search;
    }

    if( isset( $node->label ) )
    {
      $this->label = $node->label;
    }

    if( isset( $node->description ) )
    {
      $this->description = $node->description;
    }

    if( isset( $node->concepts ) )
    {
      $this->concepts = $node->concepts;
    }

    if( isset( $node->categories ) )
    {
      $this->categories = $node->categories;
    }

    if( isset( $node->unique ) )
    {
      $this->unique = $node->unique;
    }

    if( isset( $node->access ) )
    {
      $this->access = $node->access;
    }

    if( isset( $node->default ) )
    {
      $this->default = $node->default;
    }

    if( isset( $node->semantic ) )
    {
      $this->semantic = $node->semantic;
    }
    
    if( isset( $node->process ) )
    {
      $this->process = $node->process;
    }


  }//end public function import */

  /**
   * @return string
   */
  public function parse()
  {

    // parse attributes

    $attr = '';

    if($this->attrTarget)
      $attr .= ' target="'.$this->attrTarget.'" ';

    if($this->attrTargetField)
      $attr .= ' target_field="'.$this->attrTargetField.'" ';

    if($this->attrTargetAlias)
      $attr .= ' target_alias="'.$this->attrTargetAlias.'" ';

    if($this->attrType)
      $attr .= ' type="'.$this->attrType.'" ';

    if($this->attrSize)
      $attr .= ' size="'.$this->attrSize.'" ';

    if($this->attrRequired)
      $attr .= ' required="'.$this->attrRequired.'" ';

    if($this->attrValidator)
      $attr .= ' validator="'.$this->attrValidator.'" ';

    if($this->attrMinSize)
      $attr .= ' minSize="'.$this->attrMinSize.'" ';

    if($this->attrMaxSize)
      $attr .= ' maxSize="'.$this->attrMaxSize.'" ';

    if($this->attrStepSize)
      $attr .= ' step_size="'.$this->attrStepSize.'" ';


    if($this->attrIndex)
      $attr .= ' index="'.$this->attrIndex.'" ';


    // parse subnodes

    $tags = '';
    if( $this->tagDisplay )
      $tags .= $this->tagDisplay->parse();

    if( $this->tagSearch )
      $tags .= $this->tagSearch->parse();

    if( $this->tagUiElement )
      $tags .= $this->tagUiElement->parse();

    if( $this->tagLabel )
      $tags .= $this->tagLabel->parse();

    if( $this->tagDescription )
      $tags .= $this->tagDescription->parse();

    if( $this->tagConcepts )
      $tags .= $this->tagConcepts->parse();

    if( $this->tagCategories )
      $tags .= $this->tagCategories->parse();

    if( $this->tagUnique )
      $tags .= $this->tagUnique->parse();

    if( $this->tagAccess )
      $tags .= $this->tagAccess->parse();

    if( $this->tagDefault )
      $tags .= $this->tagDefault->parse();
      
    if( $this->tagProcess )
      $tags .= $this->tagProcess->parse();

    $xml = <<<XMLS
      <attribute name="{$this->name}" {$attr}  >
        {$tags}
      </attribute>
XMLS;

    return $xml;

  }//end public function parse */


}//end class LibGenfModelBdlAttribute

