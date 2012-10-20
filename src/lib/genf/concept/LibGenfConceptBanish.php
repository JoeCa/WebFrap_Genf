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
 * Wenn Banish auf einer Entität liegt können Datensätze der Entität nicht
 * gelöscht werden
 * Es wird eine Delete Flag erstellt die gesetzt wird, anstelle den datensatz
 * zu löschen
 *
 * Standardmäßig werden die Datensätze dann ausgeblendet können aber auch
 * entbannt, also wieder hergestellt werden
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfConceptBanish
  extends LibGenfConcept
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   */
  public function interpret( $conceptNode )
  {

    return array();
  }//end public function interpret */


} // end class LibGenfConceptVersion
