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
class LibCartridgeWbf
{
////////////////////////////////////////////////////////////////////////////////
// Konstanten
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var unknown_type
   */
  const OPEN    = '<?php';

  /**
   *
   * @var unknown_type
   */
  const CLOSE   = '?>';

////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the head
   * @param LibGenfBuilder $project
   * @return  string
   */
  public static function createCodeHead( $project  )
  {



    if( !$projectHead = $project->getHeader() )
    {

    $head = '<?php
/*******************************************************************************
 ____      ____  ________  ______   ________  _______          _       _______
|_  _|    |_  _||_   __  ||_   _ \ |_   __  ||_   __ \        / \     |_   __ \
  \ \  /\  / /    | |_ \_|  | |_) |  | |_ \_|  | |__) |      / _ \      | |__) |
   \ \/  \/ /     |  _| _   |  __\'.  |  _|     |  __ /      / ___ \     |  ___/
    \  /\  /     _| |__/ | _| |__) |_| |_     _| |  \ \_  _/ /   \ \_  _| |_
     \/  \/     |________||_______/|_____|   |____| |___||____| |____||_____|

                                       __.;:-+=;=_.
                                    ._=~ -...    -~:
                     .:;;;:.-=si_=s%+-..;===+||=;. -:
                  ..;::::::..<mQmQW>  :::.::;==+||.:;        ..:-..
               .:.:::::::::-_qWWQWe .=:::::::::::::::   ..:::-.  . -:_
             .:...:.:::;:;.:jQWWWE;.+===;;;;:;::::.=ugwmp;..:=====.  -
           .=-.-::::;=;=;-.wQWBWWE;:++==+========;.=WWWWk.:|||||ii>...
         .vma. ::;:=====.<mWmWBWWE;:|+||++|+|||+|=:)WWBWE;=liiillIv; :
       .=3mQQa,:=====+==wQWBWBWBWh>:+|||||||i||ii|;=$WWW#>=lvvvvIvv;.
      .--+3QWWc:;=|+|+;=3QWBWBWWWmi:|iiiiiilllllll>-3WmW#>:IvlIvvv>` .
     .=___<XQ2=<|++||||;-9WWBWWWWQc:|iilllvIvvvnvvsi|\\\'\\?Y1=:{IIIIi+- .
     ivIIiidWe;voi+|illi|.+9WWBWWWm>:<llvvvvnnnnnnn}~     - =++-
     +lIliidB>:+vXvvivIvli_."$WWWmWm;:<Ilvvnnnnonnv> .          .- .
      ~|i|IXG===inovillllil|=:"HW###h>:<lIvvnvnnvv>- .
        -==|1i==|vni||i|i|||||;:+Y1""\'i=|IIvvvv}+-  .
           ----:=|l=+|+|+||+=:+|-      - --++--. .-
                  .  -=||||ii:. .              - .
                       -+ilI+ .;..
                         ---.::....


Autor       : '.$project->author.'
Date        : {$date}
Copyright   : '.$project->copyright.'
Project     : '.$project->projectName.'
ProjectUrl  : '.$project->projectUrl.'







Licence     : '.$project->licence.'

Version: @package_version@  Revision: @package_revision@

Changes:

*******************************************************************************/
';

    }

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


}//end class LibCartridgeWbf

