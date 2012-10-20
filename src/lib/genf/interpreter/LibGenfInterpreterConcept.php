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
class LibGenfInterpreterConcept
  extends LibGenfInterpreterParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * pool for the concept interpreters
   * @var array
   */
  protected $concepts = array();


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////



  /**
   * create all default elements that inherits from entity
   * @param string $defType
   * @param DOMNode $statement
   *
   */
  public function interpret(  $statement  )
  {

    // if it's no concept... by by
    if( 'concept' != $statement->nodeName )
      return array();

    // allready interpreted
    if( $statement->hasAttribute('interpreted') )
      return array();

    $statement->setAttribute('interpreted','true');
    $type = $statement->getAttribute('name');

    if( !isset( $this->concepts[$type] ) )
    {
      if( !$className = $this->builder->getConceptClass( SParserString::subToCamelCase((string)$type) ) )
      {
        return array();
      }

      $this->concepts[$type] = new $className( $this->builder );
    }

    return $this->concepts[$type]->interpret( $statement ) ;

  }//end protected function interpret */


}//end class LibGenfInterpreter
