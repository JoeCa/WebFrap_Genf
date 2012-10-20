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
class LibGenfModelBdlConcepts
  extends LibGenfModelBdl
{


  /**
   *
   * Enter description here ...
   * @var array
   */
  protected $concepts = array();

  /**
   *
   * Enter description here ...
   * @param string $name
   * @param string $concept
   */
  public function addConcept( $name, $concept )
  {

    if( !isset($this->concepts[$name]) )
      $this->concepts[$name] = $concept;


  }//end public function addConcept */


////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import subnodes
    if( isset($node->concept) )
    {
      foreach( $node->concept as $concept )
      {
        $this->concepts[trim($concept['name'])] = new LibGenfModelBdlConcept( $concept );
      }
    }

  }//end public function import */

  /**
   * @return string
   */
  public function parse()
  {

    $concepts = '';

    foreach( $this->concepts as $concept )
    {
      $concepts .= $concept->parse();
    }


    $xml = <<<XMLS
      <concepts>
        {$concepts}
      </concepts>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlConcepts

