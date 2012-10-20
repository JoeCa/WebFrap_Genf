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
class LibParserWbfDescription
  extends LibGenfParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function parse( $node , $langKey = 'en', $defLang = 'en' )
  {

    $default = null;
    
    // check if there is a label
    if( isset( $node->description->text ) )
    {
      foreach( $node->description->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
          return trim($lang);
        elseif( trim($lang['lang']) ==  $defLang )
          $default = trim($lang);
        
      }
    }
    
    if( $default )
      return $default;

    if( isset( $node->description ) )
    {
      return trim($node->description);
    }
    
    return null;

  }//end public function getLabel */

} // end class LibParserWbfDescription
