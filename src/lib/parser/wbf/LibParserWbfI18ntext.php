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
class LibParserWbfI18ntext
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
    
    if( isset( $node['key'] ) && '' != trim($node['key'])  )
    {
      
    }
    
    // check if there is a label
    if( isset( $node->text ) )
    {
      foreach( $node->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
          return trim($lang);
        elseif( trim($lang['lang']) ==  $defLang )
          $default = trim($lang);
        
      }
    }
    
    if( $default )
      return $default;

    // if the text has no text element children we can asume that
    // there is at least text in the node
    // if not somebody made some bullshit
    return trim($node);

  }//end public function getLabel */

} // end class LibParserWbfI18ntext
