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
class LibGenfModelBdlCategories
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * Enter description here ...
   * @var array
   */
  protected $categories = array();

  // attr attributes
  protected $attrMain = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['main']) )
      $this->attrMain = trim($node['main']);

    // import subnodes
    if( isset($node->category) )
    {
      foreach( $node->category as $category )
      {
        $this->categories[trim($category['name'])] = new LibGenfModelBdlCategory( $category );
      }

    }


  }//end public function import */

  /**
   * @return string
   */
  public function parse()
  {

    // import attributes
    $main = 'default';
    if($this->attrMain)
      $main = $this->attrMain;

    $categories = '';

    foreach( $this->categories as $category )
    {
      $categories .= $category->parse();
    }


    $xml = <<<XMLS
      <categories main="{$main}"  >
        {$categories}
      </categories>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlCategories

