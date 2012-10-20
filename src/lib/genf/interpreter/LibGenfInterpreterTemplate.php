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
class LibGenfInterpreterTemplate
  extends LibGenfInterpreterParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @var array
   */
  protected $defPool       = array();

  /**
   * @var array
   */
  protected $matchingNodes = array
  (
    'ui',
  );

  /**
   * @var array
   */
  protected $matchingPNodes = array
  (
    'from',
    'listing',
  );

  /**
   *
   * @var LibGenfTreeRootMask
   */
  protected $templateRoot   = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function init()
  {



  }//end public function init */


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create all default elements that inherits from entity
   *
   * @param DOMNode $xmlNode
   *
   */
  public function interpret( $xmlNode  )
  {

    if( !$this->templateRoot )
      $this->templateRoot = $this->builder->getRoot('mask');


    if( !method_exists( $xmlNode, 'getAttribute' ) )
      return null;

    if
    (
      !in_array( $xmlNode->nodeName , $this->matchingNodes )
      && ( isset($xmlNode->parentNode) && !in_array( $xmlNode->parentNode->nodeName , $this->matchingPNodes  ) )
    )
      return null;

    if( !$template = $xmlNode->getAttribute('template') )
      return null;


    // zuerste einmal den übergebenen knoten interpretieren
    if( $replaced  = $this->interpretNode( $template, $xmlNode ))
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
   *
   */
  protected function interpretNode( $defType, $statement  )
  {

    if( !$typeNode = $this->searchParent($statement) )
    {
      return null;
    }

    $nodeName = $typeNode->getName();

    if( $nodeName == 'management' )
    {
      $source = $typeNode['source'];
    }
    elseif ( $nodeName == 'widget' )
    {
      $source = $typeNode['source'];
    }
    elseif ( $nodeName == 'entity' )
    {
      $source = $typeNode['name'];
    }
    elseif ( $nodeName == 'ref' )
    {

      if( 'manytomany' == strtolower($typeNode['relation']) )
      {
        $source = $typeNode->connection['name'];
      }
      else
      {
        $source = $typeNode->target['name'];
      }

    }

    if( $template = $this->templateRoot->getTemplate( $defType, $source, $statement->nodeName  ) )
    {

      $template = $statement->ownerDocument->importNode($template,true);
      $statement->parentNode->replaceChild($template, $statement);

      return $template;

    }

    return null;

  }//end protected function interpretNode */

  /**
   *
   * @param DOMNode $node
   * @return SimpleXMLElement null im Fehlerfall
   */
  public function searchParent( $node )
  {


    if( $node->ownerDocument === $node->parentNode )
    {
      return null;
    }

    if( in_array( $node->nodeName, array( 'entity', 'management', 'ref', 'widget' ) )  )
    {
      return simplexml_import_dom($node);
    }

    return $this->searchParent($node->parentNode);

  }//end public function searchParent */

}//end class LibGenfInterpreterTemplate
