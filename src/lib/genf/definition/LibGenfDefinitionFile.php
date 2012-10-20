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
class LibGenfDefinitionFile
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return LibGenfModelBdlAttribute
   */
  public function interpret( $statement, $parentNode )
  {

    $nodes = array();

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'file';
      $vars->size       = '250';
      $vars->type       = 'text';
      $vars->validator  = 'file';

      $vars->uiElement->type  = 'file';

      $nodes[] = $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }
    
    // der mimetype
    $xml = <<<XMLS
    <attribute name="mimetype" type="text" size="20"   >
      <uiElement type="hidden" />
    </attribute>
XMLS;
    
    $nodes[] = $this->addNode($xml, $parentNode);
    
    // größe der datei
    $xml = <<<XMLS
      <attribute name="file_size" type="int"  >
        <docu>
          <text lang="de" >Größe der Datei in Bytes</text>
        </docu>
      </attribute>
XMLS;


    $nodes[] = $this->addNode($xml,$parentNode);
    
    // hash
    $xml = <<<XMLS
      <attribute name="file_hash" type="text" size="32"  >
        <docu>
          <text lang="de" >Der SHa1 Hash für die hochgeladene Datei.</text>
        </docu>
        <uiElement type="hidden" />
      </attribute>
XMLS;


    $nodes[] = $this->addNode($xml,$parentNode);
    
    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionFile
