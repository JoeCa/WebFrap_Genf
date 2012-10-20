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
class LibParserWbfLabel
  extends LibGenfParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function parse( $node , $langKey = 'en', $shift = false , $defLang = 'en' )
  {

    // check if there is a label
    if( isset( $node->label->text ) )
    {
      foreach( $node->label->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
          return trim($lang);
      }
    }

    if( isset( $node->label ) )
    {
      return trim($node->label);
    }
    
    return SParserString::subToName( (string)$node['name'] , $shift );

  }//end public function getLabel */

} // end class LibParserWbfLabel
