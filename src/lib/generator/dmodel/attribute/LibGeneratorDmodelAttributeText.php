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
 * @subpackage Genf
 */
class LibGeneratorDmodelAttributeText
  extends LibGeneratorDmodelAttribute
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param LibGenfTreeNodeAttribute $attribute
   */
  public function buildSetupField( $attribute )
  {

    $name         = $attribute->ccName();

    $description  = $this->escapeString( (string)$attribute->description() );

    if( trim( $description ) != '' )
    {
      $description = ' "'.$description.'" ';
    }

    /*
    if( trim($attribute['maxSize']) != '' )
      $maxSize = ' --max '.(string)$attribute['maxSize'];
    else
      $maxSize = '';

    if( trim($attribute['minSize']) != '' )
      $minSize = ' --min '.(string)$attribute['minSize'];
    else
      $minSize = '';

    if( trim($attribute['required']) == 'true' )
      $required = ' --notNull ';
    else
      $required = '';
    */


    $code = "{$name}: String {$description}".NL;

    return $code;

  }//end public function buildSetupField */


} // end class LibGeneratorDmodelAttributeText
