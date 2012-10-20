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
class LibGenfInterpreterDefinition
  extends LibGenfInterpreterParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * 
   * @var array
   */
  protected $defPool         = array();


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create all default elements that inherits from entity
   * @param string $defType
   * @param DOMNode $statement
   *
   */
  public function interpret( $xmlNode  )
  {
    
    // check if the given node is a valid definition
    if
    ( 
      !method_exists( $xmlNode, 'getAttribute' ) 
        || !$definition = $xmlNode->getAttribute('is_a') )
    {
      return;
    }
    
    // zuerste einmal den übergebenen knoten interpretieren
    if( $replaced  = $this->interpretNode( $definition, $xmlNode ))
    {
      
      // dann muss noch die rückgabe interpretiert werden
      // die rückgabe muss nocheinmal durch den kompletten interpreter
      if( is_array($replaced) )
      {
        foreach( $replaced as $replacedNode )
        {
          $this->interpreter->interpret( $replacedNode  );
        }
      }
      else
      {
        $this->interpreter->interpret( $replaced  );
      }

    }


  }//end protected function interpret */

  
  /**
   * create all default elements that inherits from entity
   * @param string $defType
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   *
   */
  protected function interpretNode( $defType, $statement  )
  {

    $defType = ucfirst($defType);

    if( !isset( $this->defPool[$defType] ) )
    {
      if( !$className = $this->builder->getDefinitionClass( SParserString::subToCamelcase($defType) ) )
      {
        Error::report
        (
          'Requested nonexisting Definition: '.$defType.'. Please check the Name '
            .' or create a class LibGenfDefinition'.$defType.' to define what you mean.'
        );

        // remove the definition for not getting problems during compilation
        $statement->parentNode->removeChild($statement);
        return array();
      }

      $this->defPool[$defType] = new $className( $this->builder );

    }

    return $this->defPool[$defType]->interpret( $statement, $statement->parentNode ) ;

  }//end protected function interpretNode */

}//end class LibGenfInterpreter
