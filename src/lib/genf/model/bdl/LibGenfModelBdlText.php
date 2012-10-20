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
class LibGenfModelBdlText
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // attr attributes
  /**
   * Enter description here ...
   * @var string
   */
  protected $attrLang      = null;
  
  /**
   * Enter description here ...
   * @var string
   */
  protected $attrValue     = '';

////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function __construct( $lang, $node = null )
  {

    $this->attrLang = $lang;

    if($node)
      $this->import( $node );

  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->attrValue;
  }//end public function __toString */

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $value
   */
  public function setValue( $value )
  {

    if( is_null($this->attrValue) )
      $this->attrValue = $value;

  }//end public function setValue */

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    if( is_scalar($node) )
    {
      $this->attrValue  = trim($node);
    }
    else
    {
      // import attributes
      if( is_object($node) && isset($node['lang']) )
        $this->attrLang = trim($node['lang']);

      $this->attrValue  = trim($node);
    }


  }//end public function import */


  /**
   * @return string
   */
  public function parse()
  {

    $lang = '';
    
    if($this->attrLang)
      $lang = ' lang="'.$this->attrLang.'" ';

    $xml = <<<XMLS
      <text {$lang} >{$this->attrValue}</text>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlText

