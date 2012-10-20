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
class LibGenfNameTarget
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
      $label = LibGenfBuild::getInstance()->interpreter->getLabel( $name );

       if( $label )
        $this->label = $label;
      else if( isset( $name['name'] ) )
        $this->label = SParserString::subToName( trim($name['name']) );

        
      $target = null;
      if( isset( $name['target'] ) )
      {
        $target = trim($name['target']);
      }
      else if( $name['ref'] )
      {
        $target = trim($name['ref']);
      }
      

      if( isset( $name['name'] ) )
        $name = trim($name['name']);
      else
        $name = $target;
        
      if( !$target )
      {
        Debug::console( "Got not target",null, true );
      }

    }
    else
    {
      $this->label  = SParserString::subToName($name);
      $target       = $name;
    }

    $this->name     = $name;
    $this->key      = SParserString::subToCamelCase($name);

    $this->target   = $target;
    $this->class    = SParserString::subToCamelCase($target);
    $this->module   = SParserString::getDomainName($target);
    $this->model    = SParserString::subToCamelCase( SParserString::removeFirstSub($target) ) ;


    $tmp = explode('_',$target);
    array_shift($tmp);

    $this->modelSub        = implode('_',$tmp);

    $this->classPath       = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->i18nKey         = $this->lower('module').'.'.SParserString::subBody($target).'.';

  }//end public function parse */


}//end class LibGenfNameTarget

