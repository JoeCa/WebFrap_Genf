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
 * Alle relevanten Namenselemente zum benamen der Attribute relevanten
 * Architekturelemente im Code
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameAttribute
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

  public function label( $lang = 'en' )
  {
    return LibGenfBuild::getInstance()->interpreter->getLabel( $this->node , $lang , true  );
  }

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $name , $params = array() )
  {

    if( is_object( $name ) )
    {

      $this->node         = $name;

      $label = LibGenfBuild::getInstance()->interpreter->getLabel( $name );

       if( $label )
        $this->label = $label;
      else
        $this->label = SParserString::subToName( trim($name['name']) );

      $name = trim($name['name']);
    }
    else
    {

      $name = trim($name);

      $this->label         = SParserString::subToName($name);
    }

    $this->name            = $name;
    $this->source          = $name;

    $this->entity          = SParserString::subToCamelCase($name);
    $this->class           = SParserString::subToCamelCase($name);
    $this->module          = SParserString::getDomainName($name);
    $this->model           = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;


    $tmp = explode('_',$name);
    array_shift($tmp);

    $this->modelSub        = implode('_',$tmp);
    $this->entityPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->entityUrl       = $this->module.'.'.$this->model;

    $this->classPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->i18nKey         = $this->lower('module').'.'.SParserString::subBody($name).'.';
    $this->i18ntext        = $this->lower('module').'.'.SParserString::subBody($name).'.label';

  }//end public function parse */


}//end class LibGenfName

