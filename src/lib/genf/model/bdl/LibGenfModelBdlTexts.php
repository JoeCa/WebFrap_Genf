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
class LibGenfModelBdlTexts
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  protected $texts = array();

  /**
   *
   * @var string
   */
  protected $value = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $key
   */
  public function __get( $key )
  {

    if( !isset( $this->texts[$key]  ) )
    {
      $this->texts[$key] = new LibGenfModelBdlText( $key );
    }

    return $this->texts[$key];

  }//end public function __get */

  /**
   * @param string $key
   * @param string $value
   */
  public function __set( $key, $value )
  {

    if(empty($value))
      return;

    if( !isset( $this->texts[$key]  ) )
    {
      $this->texts[$key] = new LibGenfModelBdlText( $key , $value );
    }

    if( is_scalar($value) )
    {
      $this->texts[$key]->setValue($value);
    }
    else
    {
      $this->texts[$key]->import($value);
    }


  }//end public function __set */


  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import subnodes
    if( isset( $node->text ) )
    {
      foreach( $node->text as $text )
      {

        $lang = (isset($text['lang']) && '' == trim($text['lang']) )
          ? trim($text['lang'])
          : 'en';

        $this->texts[$lang] = new LibGenfModelBdlText( $lang, trim($text) );
      }
    }
    else
    {
      $this->texts['en'] = new LibGenfModelBdlText( 'en', trim($node) );
    }

  }//end public function import */



}//end class LibGenfModelBdlTexts

