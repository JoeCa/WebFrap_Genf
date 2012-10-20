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
class LibGenfInterpreter
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfInterpreterI18n
   */
  public $text = null;

  /**
   *
   * @var array<LibGenfDefinition>
   */
  protected $defPool      = array();

  /**
   *
   * @var array<LibGenfDefinition>
   */
  protected $conceptPool  = array();

  /**
   *
   * @var LibGenfBuild
   */
  protected $builder      = null;

  /**
   * list of onload interpreters
   * @var array
   */
  protected $interpreters = array();

  /**
   * list of onload post interpreters
   * @var array
   */
  protected $postInterpreters = array();

  /**
   * List with post processors
   * @var array
   */
  protected $postProcessors = array();


////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param LibGenfBuild $builder
   * @param LibGenfTree $tree
   */
  public function __construct( $builder  )
  {
    $this->text     = new LibGenfInterpreterI18n();
    $this->builder  = $builder;


    $this->loadInterpreters();

  }//end public function __construct

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param SimpleXmlElement $i18nNode
   * @param string $langKey
   * @return string
   */
  public function i18nText( $i18nNode , $langKey = 'en' )
  {

    return $this->text->i18nText( $i18nNode, $langKey );

  }//end public function i18nText */

  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function getLabel( $node , $langKey = 'en' , $shift = false )
  {

    return $this->text->getLabel( $node, $langKey , $shift );

  }//end public function getLabel */

  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function getPlabel( $node , $langKey = 'en' , $shift = false )
  {

    return $this->text->getPlabel( $node, $langKey , $shift );

  }//end public function getPlabel */

  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function getInfo( $node , $langKey = 'en' , $shift = false )
  {

    return $this->text->getInfo( $node, $langKey , $shift );

  }//end public function getInfo */


  /**
   * @param SimpleXmlElement $node
   * @param string $lang
   */
  public function nameToLabel( $string , $lower = false )
  {

    $label = SParserString::subToName( $string , false );

    if( $lower )
    {
      return strtolower( $label );
    }
    else
    {
      return $label;
    }

  }//end public function nameToLabel */

  /**
   * @param DOMNode $xmlNode
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   *
   */
  public function interpret( $xmlNode )
  {

    // interpret multiple nodes
    if( is_array( $xmlNode ) || ( is_object($xmlNode) &&  $xmlNode instanceof DOMNodelist ) )
    {
      foreach( $xmlNode as $subNode )
      {
        $this->interpret( $subNode );
      }
    }
    else if( !is_object($xmlNode) || !$xmlNode instanceof DOMNode )
    {
      Error::report('Got non object in treeroot interpreter',$xmlNode);
      return null;
    }
    else
    {
      $this->interpretNode( $xmlNode );
    }

  }//end protected function interpret */


  /**
   * @param DOMNode $xmlNode
   *
   */
  protected function interpretNode( $xmlNode )
  {

    if( isset( $xmlNode->tagName ) )
    {
      if( 'xInclude' == $xmlNode->tagName  )
      {
        $xmlNode = $this->replaceByIncludePath( $xmlNode );
      }
    }

    foreach( $this->interpreters as $interpreter )
    {
      $interpreter->interpret( $xmlNode );
    }

    /// TODO maybe some leafes are checked two times now, check that
    // if this node has no children check it
    if( !$xmlNode->hasChildNodes() )
    {
      return null;
    }


    $tmpChilds      = array();
    foreach( $xmlNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {
      $this->interpret($childNode);
    }//end foreach */


  }//end protected function interpret */

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param DOMNode $xmlNode
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   *
   */
  public function postInterpret( $xmlNode )
  {

    // interpret multiple nodes
    if( is_array( $xmlNode ) || ( is_object($xmlNode) &&  $xmlNode instanceof DOMNodelist ) )
    {
      foreach( $xmlNode as $subNode )
      {
        $this->postInterpret( $subNode );
      }
    }
    else if( !is_object($xmlNode) || !$xmlNode instanceof DOMNode )
    {
      Error::report('Got non object in treeroot interpreter',$xmlNode);
      return null;
    }
    else
    {
      $this->postInterpretNode( $xmlNode );
    }

  }//end protected function interpret */


  /**
   * @param DOMNode $xmlNode
   *
   */
  protected function postInterpretNode( $xmlNode )
  {

    foreach( $this->postInterpreters as $interpreter )
    {
      $interpreter->postInterpret( $xmlNode );
    }

    /// TODO maybe some leafes are checked two times now, check that
    // if this node has no children check it
    if( !$xmlNode->hasChildNodes() )
    {
      return null;
    }


    $tmpChilds      = array();
    foreach( $xmlNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {
      $this->postInterpret($childNode);
    }//end foreach */


  }//end protected function interpret */


  /**
   * check if this node is a valid DOM Element Node and Nothing else
   * @param DOMElement $element
   */
  public function isNode( $element )
  {

    // skip invalid childnodes
    if( $element->nodeType != XML_ELEMENT_NODE )
      return false;
    else
      return true;

  }//end public function isNode */


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  protected function loadInterpreters()
  {

    $this->interpreters['definition']     = new LibGenfInterpreterDefinition($this, $this->builder);
    $this->interpreters['concept']        = new LibGenfInterpreterConcept($this, $this->builder);
    $this->interpreters['template']       = new LibGenfInterpreterTemplate($this, $this->builder);
    $this->postInterpreters['condition']  = new LibGenfInterpreterCondition($this, $this->builder);

  }//end protected function loadInterpreters */

  /**
   * @param DOM $incObj
   */
  public function replaceByIncludePath( $incObj )
  {

    $path = explode( '::', trim( $incObj->getAttribute('src') ) );

    $filePath  = $path[0];
    $xPath     = '//includes/'.$path[1];
    
    Debug::console( "X INCLUDE || {$filePath} {$xPath}" );

    $incDomDoc = $this->builder->loadIncludeDocument( $filePath );
    $incXpath  = new DOMXpath( $incDomDoc );

    $found = $incXpath->query( $xPath );

    // include must be exactly 1 match
    if( $found->length < 1 )
    {
      Error::addError( 'Invalid Include: '.$filePath.' '.$xPath );
      return $incObj;
    }

    $newObj = $found->item(0);

    $newObj = $incObj->ownerDocument->importNode( $newObj, true );
    $parentNode = $incObj->parentNode;
    $incObj->parentNode->replaceChild( $newObj, $incObj );
    
    if( $found->length > 1 )
    {
      for( $pos = 1; $pos < $found->length; ++$pos  )
      {
        $nextObj = $parentNode->ownerDocument->importNode( $found->item($pos), true );
        $parentNode->appendChild( $nextObj );
      }
    }

    return $newObj;

  }//end public function replaceByIncludePath */

/*//////////////////////////////////////////////////////////////////////////////
// Debug Console
//////////////////////////////////////////////////////////////////////////////*/

  public function getDebugDump()
  {
    return array
    (

    );
  }

}//end class LibGenfInterpreter
