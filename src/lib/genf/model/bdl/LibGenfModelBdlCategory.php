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
class LibGenfModelBdlCategory
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // attr attributes
  protected $attrName = null;

  // attr nodes
  protected $tagAccess       = null;
  protected $tagDescription  = null;
  protected $tagLabel        = null;
  protected $tagLayout       = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['name']) )
      $this->attrName = trim($node['name']);

    // import subnodes
    if( isset( $node->access ) )
    {
      $this->access = $node->access;
    }

    if( isset( $node->description ) )
    {
      $this->description = $node->description;
    }

    if( isset( $node->label ) )
    {
      $this->label = $node->label;
    }

    if( isset( $node->layout ) )
    {
      $this->layout = $node->layout;
    }



  }//end public function import */

  /**
   * @return string
   */
  public function parse()
  {

    if( !$this->attrName )
    {
      $this->attrName = 'default';
    }

    // parse subnodes
    $access       = '';
    if($this->tagAccess )
      $access     = $this->tagAccess->parse();

    $description  = '';
    if($this->tagDescription )
      $description = $this->tagDescription->parse();

    $label      = '';
    if($this->tagLabel )
      $label    = $this->tagLabel->parse();

    $layout      = '';
    if($this->tagLayout )
      $layout    = $this->tagLayout->parse();


    $xml = <<<XMLS
      <category name="{$this->attrName}"  >

        {$access}
        {$description}
        {$label}
        {$layout}

      </category>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlCategory

