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
 * @subpackage ModGenf
 */
class LibParserXmi
{

  /**
   * parse an Umbrello Modell to Metamodel
   *
   * @param string $modeFile
   * @param string $metaFilename
   * @return boolean
   */
  public function parseUmbrello( $modelFile , $metaFilename = null )
  {

    try
    {
      $parser = new LibParserXmiUmbrello( $modelFile );
      $xml = $parser->parse();

      if($metaFilename)
      {
        $parser->save( $metaFilename );
      }

      return $xml;
    }
    catch( LibParser_Exception $exc )
    {
      Message::addError($exc->getMessage());
      return null;
    }

  }

} // end class LibParserXmi

