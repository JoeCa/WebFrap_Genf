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
class LibGenfModelBdlDisplay
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // attr attributes
  protected $attrType         = null;
  protected $attrAction       = null;
  protected $attrField        = null;
  protected $attrPriority     = null;

  // node attributes
  protected $tagText          = null;
  protected $tagTable         = null;
  protected $tagTitle         = null;
  protected $tagList          = null;
  protected $tagListing       = null;
  protected $tagTreetable     = null;
  protected $tagBlock         = null;
  protected $tagOverview      = null;
  protected $tagSelection     = null;
  protected $tagInput         = null;
  protected $tagTree          = null;
  protected $tagWebservice    = null;
  protected $tagAutocomplete  = null;
  protected $tagExport        = null;
  protected $tagDsource       = null;


////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $key
   */
  public function __get( $key )
  {

    $propName = 'tag'.ucfirst($key);
    $attrName = 'attr'.ucfirst($key);

    if( property_exists( $this , $propName ) )
    {
      if( is_null( $this->$propName ) )
      {
        $className = 'LibGenfModelBdlDisplaynode';
        $this->$propName        = new $className();
        $this->$propName->name  = $key;
      }

      return $this->$propName;
    }
    else if( property_exists( $this , $attrName ) )
    {
      return $this->$attrName;
    }
    else
    {
      throw new LibGenf_Exception('try to request an invalid tag '.$key.' on '.get_class($this) );
    }

  }//end public function __get */

  /**
   * @param string $key
   * @param string $value
   */
  public function __set( $key, $value )
  {

    $propName = 'tag'.ucfirst($key);
    $attrName = 'attr'.ucfirst($key);

    if( property_exists( $this , $propName ) )
    {
      if( is_null( $this->$propName ) )
      {
        $className = 'LibGenfModelBdlDisplaynode';
        $this->$propName = new $className();
        $this->$propName->name  = $key;
      }

      $this->$propName->import($value);
    }
    else if( property_exists( $this , $attrName ) )
    {
      if( is_null($this->$attrName) )
        $this->$attrName = $value;
    }
    else
    {
      throw new LibGenf_Exception('try to write in invalid tag '.$key.' on '.get_class($this) );
    }

  }//end public function __set */

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

    if( isset($node['field']) )
      $this->attrField  = trim($node['field']);

    if( isset($node['priority']) )
      $this->attrPriority  = trim($node['priority']);

    if( isset($node['action']) )
      $this->attrAction  = trim($node['action']);

    // import nodes
    if( isset( $node->text ) )
    {
      $this->text = $node->text;
    }
    if( isset( $node->table ) )
    {
      $this->table = $node->table;
    }
    if( isset( $node->title ) )
    {
      $this->title = $node->title;
    }
    if( isset( $node->list ) )
    {
      $this->list = $node->list;
    }
    if( isset( $node->listing ) )
    {
      $this->listing = $node->listing;
    }
    if( isset( $node->treetable ) )
    {
      $this->treetable = $node->treetable;
    }
    if( isset( $node->block ) )
    {
      $this->block = $node->block;
    }
    if( isset( $node->overview ) )
    {
      $this->overview = $node->overview;
    }
    if( isset( $node->selection ) )
    {
      $this->selection = $node->selection;
    }
    if( isset( $node->input ) )
    {
      $this->input = $node->input;
    }
    if( isset( $node->tree ) )
    {
      $this->tree = $node->tree;
    }
    if( isset( $node->webservice ) )
    {
      $this->webservice = $node->webservice;
    }
    if( isset( $node->autocomplete ) )
    {
      $this->autocomplete = $node->autocomplete;
    }
    if( isset( $node->export ) )
    {
      $this->export = $node->export;
    }
    if( isset( $node->dsource ) )
    {
      $this->dsource = $node->dsource;
    }

  }//end public function import */

  /**
   * @return string
   */
  public function parse()
  {

    // parse attributes
    $attrs   = '';

    if($this->attrType)
      $attrs .= ' type="'.$this->attrType.'" ';

    if($this->attrField)
      $attrs .= ' field="'.$this->attrField.'" ';

    if($this->attrPriority)
      $attrs .= ' priority="'.$this->attrPriority.'" ';

    if($this->attrAction)
      $attrs .= ' action="'.$this->attrAction.'" ';

    // parse subnodes
    $nodes   = '';

    if($this->tagText)
      $nodes .= $this->tagText->parse().NL;

    if($this->tagTable)
      $nodes .= $this->tagTable->parse().NL;

    if($this->tagTitle)
      $nodes .= $this->tagTitle->parse().NL;

    if($this->tagList)
      $nodes .= $this->tagList->parse().NL;

    if($this->tagListing)
      $nodes .= $this->tagListing->parse().NL;

    if($this->tagTreetable)
      $nodes .= $this->tagTreetable->parse().NL;

    if($this->tagBlock)
      $nodes .= $this->tagBlock->parse().NL;

    if($this->tagOverview)
      $nodes .= $this->tagOverview->parse().NL;

    if($this->tagSelection)
      $nodes .= $this->tagSelection->parse().NL;

    if($this->tagInput)
      $nodes .= $this->tagInput->parse().NL;

    if($this->tagTree)
      $nodes .= $this->tagTree->parse().NL;

    if($this->tagWebservice)
      $nodes .= $this->tagWebservice->parse().NL;

    if($this->tagAutocomplete)
      $nodes .= $this->tagAutocomplete->parse().NL;

    if($this->tagExport)
      $nodes .= $this->tagExport->parse().NL;

    if($this->tagDsource)
      $nodes .= $this->tagDsource->parse().NL;

    $xml = <<<XMLS
      <display {$attrs} >
        {$nodes}
      </display>
XMLS;

    return $xml;

  }//end public function parse */



}//end class LibGenfModelBdlDisplay

