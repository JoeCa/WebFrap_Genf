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
class LibGenfModelBdlSearch
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  // attr attributes

  /**
   *
   * @var string
   */
  protected $attrType   = null;

  /**
   *
   * @var string
   */
  protected $attrFree   = null;

  /**
   *
   * @var string
   */
  protected $attrIndex  = null;


  /**
   * @var string
   */
  protected $tagBegin   = null;

////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['type']) )
      $this->attrType = trim($node['type']);

    // import attributes
    if( isset($node['free']) )
      $this->attrFree = trim($node['free']);


    // import attributes
    if( isset($node['index']) )
      $this->attrIndex = trim($node['index']);

     // import attributes
    if( isset($node->begin) )
      $this->begin = $node->begin;

  }//end public function import */


  /**
   * @return string
   */
  public function parse()
  {

    $attr = '';
    if($this->attrType)
      $attr .= ' type="'.$this->attrType.'" ';

    if($this->attrIndex)
      $attr .= ' index="'.$this->attrIndex.'" ';

    if( $this->attrFree && 'false' != $this->attrFree )
      $attr .= ' free="'.$this->attrFree.'" ';

    $tag = '';
    if( $this->tagBegin )
      $tag .= '<begin />';

    $xml = <<<XMLS
      <search {$attr} >
        {$tag}
      </search>
XMLS;

    return $xml;

  }//end public function parse */


}//end class LibGenfModelBdlSearch

