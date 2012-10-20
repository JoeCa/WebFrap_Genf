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
class LibGenfNameGuess
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
  public function parse( $name , $params = array() )
  {

    if( is_object( $name ) )
    {
      $this->label = LibGenfBuild::getInstance()->interpreter->getLabel( $name );

      if( isset( $name['name'] ) )
        $name = trim($name['name']);
      else
        $name = null;

    }
    else
    {
      $this->label           = SParserString::subToName($name);
    }

    if( $name )
    {
      $this->name            = $name;
      $this->key             = SParserString::subToCamelCase($name);
    }

    /*
    $this->class           = SParserString::subToCamelCase($type);
    $this->module          = SParserString::getDomainName($type);
    $this->model           = SParserString::subToCamelCase( SParserString::removeFirstSub($type) ) ;


    $tmp = explode('_',$type);
    array_shift($tmp);

    $this->modelSub        = implode('_',$tmp);

    $this->classPath       = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->i18nKey         = $this->lower('module').'.'.SParserString::subBody($type).'.';
    */

  }//end public function parse */


}//end class LibGenfName

