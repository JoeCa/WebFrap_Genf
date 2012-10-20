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
class LibCartridgePhp
{
////////////////////////////////////////////////////////////////////////////////
// Konstanten
////////////////////////////////////////////////////////////////////////////////


  const OPEN = '<?php';

  const CLOSE = '?>';


////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the head
   * @return  string
   */
  public static function createCodeHead( $author, $copyright  )
  {

    $head = <<<CARTRIDGE
<?php
/*******************************************************************************
*
* @author      : {$author}
* @date        : {@date@}
* @copyright   : {$copyright}
* @project     : Webfrap Web Frame Application
* @projectUrl  : http://webfrap.net
*
* @licence     : {@licence@}
* 
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/

CARTRIDGE;

  if( MARK_NEW_CODE )
    $head .= '//THIS IS NEW CODE '.NL;

  return $head;


  }//end public static function createCodeHead */

  /**
   * parse the footer
   * @return string
   */
  public static function parseFoot()
  {

    return NL;

  }//end public static function parseFoot */

  /**
   * parse a code seperator banner with text
   *
   * @param string $content
   * @return string
   */
  public static function parseCodeSeperator( $content )
  {


    $code='
////////////////////////////////////////////////////////////////////////////////
// '.$content.'
////////////////////////////////////////////////////////////////////////////////
    ';

    return $code;

  }//end public static function parseCodeSeperator */


}//end class LibCartridgePhp

