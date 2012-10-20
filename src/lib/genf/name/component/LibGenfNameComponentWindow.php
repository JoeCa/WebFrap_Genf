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
 * Eine Name Lib für die Namings
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameComponentWindow
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $name, $params = array()  )
  {
    $this->parse($name, $params);
  }//end public function __construct */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $node , $params = array() )
  {

    $this->name       = trim($node['name']);
    $this->source     = trim($node['src']);

    //$this->label    = $label;
    $this->class      = SParserString::subToCamelCase($this->name);

    $this->module     = SParserString::getDomainName($this->name);
    $this->model      = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;


    $tmp = explode('_',$this->name);
    array_shift($tmp);

    $this->classPath = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl  = $this->module.'.'.$this->model;

    $this->i18nKey   = $this->lower('module').'.'.SParserString::subBody($this->name).'.';


  }//end public function parse */


}//end class LibGenfName

