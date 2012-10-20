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
class LibParserModel
{

  /**
   * parse an Umbrello Modell to Metamodel
   *
   * @param string $modeFile
   * @param string $metaFilename
   * @return boolean
   */
  public function parseModel( $type , $version,  $modelFile , $metaFilename = null )
  {

    $className = 'LibParserModel'.$type.$version;

    try
    {
      $parser = new $className( $modelFile );
      $xml = $parser->parse();

      if($metaFilename)
        $parser->save( $metaFilename );

      return simplexml_load_string($xml);
    }
    catch( LibParser_Exception $exc )
    {
      Message::addError($exc->getMessage());
      return null;
    }

  }//end public function parseModel

} // end class LibParserXmi

