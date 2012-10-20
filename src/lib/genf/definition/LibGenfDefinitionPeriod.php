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
class LibGenfDefinitionPeriod
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    $vars = $this->checkAttribute( $statement, $parentNode  );


    $vars->name = 'period';
    $vars->type = 'date';

    $list = array();

    $xml = <<<XMLS
      <attribute name="{$vars->name}_start" type="date"  >

        <label>
          <text lang="de" >Start Periode</text>
          <text lang="en" >Start Period</text>
        </label>

        {$vars->search}

        <description>
          <text lang="de" >Start Datum für eine Periode</text>
          <text lang="en" >Start Date for a Period</text>
        </description>

      </attribute>
XMLS;


    $list[] = $this->replaceDefinition($xml, $statement, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$vars->name}_end" type="date"  >

        <label>
          <text lang="de" >Perioden Ende</text>
          <text lang="en" >Period End</text>
        </label>

        {$vars->search}

        <description>
          <text lang="de" >End Datum für eine Periode</text>
          <text lang="en" >End Date for period</text>
        </description>

      </attribute>
XMLS;


    $list[] = $this->addNode($xml,$parentNode);

    return $list;

  }//end public function interpret */


} // end class LibGenfDefinitionPeriod
