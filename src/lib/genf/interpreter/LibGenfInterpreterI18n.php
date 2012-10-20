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
class LibGenfInterpreterI18n
{
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param SimpleXmlElement $i18nNode
   * @param string $langKey
   * @return string
   */
  public function i18nText( $i18nNode , $langKey = 'en' )
  {

    foreach( $i18nNode->text as $lang )
    {
      if( isset($lang['lang']) && trim($lang['lang']) ==  $langKey )
        return trim($lang);
    }

    return null;

  }//end public function i18nText */

  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function getLabel( $node , $langKey = 'en', $shift = false )
  {

    if( is_string( $node ) )
    {
      return SParserString::subToName( $node, $shift );
    }

    // check if there is a label
    if( isset( $node->label->text ) )
    {

      foreach( $node->label->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey && '' != trim($lang) )
          return trim($lang);
      }

    }

    /*
    if( isset( $node->label ) )
    {
      return trim($node->label);
    }
    */

    if( isset( $node['label'] ) )
      return SParserString::subToName( (string)$node['label']  );
    else
      return SParserString::subToName( (string)$node['name'], $shift );

  }//end public function getLabel */
  
  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function getPlabel( $node , $langKey = 'en', $shift = false )
  {

    if( is_string( $node ) )
    {
      return SParserString::subToName( $node, $shift );
    }

    // check if there is a label
    if( isset( $node->plabel->text ) )
    {

      foreach( $node->plabel->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey && '' != trim($lang) )
          return trim($lang);
      }

    }

    /*
    if( isset( $node->label ) )
    {
      return trim($node->label);
    }
    */

    if( isset( $node['plabel'] ) )
      return SParserString::subToName( (string)$node['plabel']  );
    else
    {
      $label = $this->getLabel( $node , $langKey, $shift );
      
      if( 's' != strtolower(substr($label, -1)) )
      {
        return $label.'s';
      }
      else
      {
        return $label;
      }
      
      
    }
      
  }//end public function getPlabel */

  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function getComment( $node , $langKey = 'en' )
  {

    // check if there is a label
    if( isset( $node->comment->text ) )
    {

      foreach( $node->comment->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
          return trim($lang);
      }

    }

    /*
    if( isset( $node->comment ) )
    {
      return trim($node->comment);
    }
    */

   return null;

  }//end public function getComment */

  /**
   * @param SimpleXmlElement $node
   * @param string $langKey
   */
  public function getDescription( $node , $langKey = 'en' )
  {

    // check if there is a label
    if( isset( $node->description->text ) )
    {

      foreach( $node->description->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
          return trim($lang);
      }

    }

    /*
    if( isset( $node->description ) )
    {
      return trim($node->description);
    }
    */

   return null;

  }//end public function getDescription */

  /**
   * @param SimpleXmlElement $node
   * @param string $langKey
   */
  public function getInfo( $node , $langKey = 'en' )
  {

    // check if there is a label
    if( isset( $node->info->text ) )
    {

      foreach( $node->info->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
          return trim($lang);
      }

    }

   return null;

  }//end public function getInfo */

}//end class LibGenfInterpreter
