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
class LibBdlFilterParser
  extends LibBdlParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibBdlFilterLexer
   */
  protected $lexer      = null;

////////////////////////////////////////////////////////////////////////////////
// init methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * load the lexer
   */
  public function loadLexer()
  {

    $this->lexer = new LibBdlFilterLexer( );

  }//end public function loadLexer */

  /**
   *
   */
  public function loadRegistry()
  {
    $this->registry = new LibBdlFilterRegistry(  'LibBdlFilter' , $this->lexer );

  }//end public function loadRegistry */

////////////////////////////////////////////////////////////////////////////////
// parser method
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $rawCode
   */
  public function analyse( $rawCode )
  {

    $this->lexer->split( $rawCode );

  }//end public function parseCode */


  /**
   * @param string $rawCode
   */
  public function getAffectedFields(  )
  {

    $parser   = $this->registry->getSubParser('Filter');
    return $parser->parse();

  }//end public function getAffectedFields */

  /**
   * @param string $rawCode
   */
  public function compileSql( $type, $node  )
  {

    $parser   = $this->registry->getSubParser('Sql'.ucfirst($type));
    return $parser->parse( $node );

  }//end public function getAffectedFields */

  /**
   * @param string $rawCode
   */
  public function compileCode( $type  )
  {

    $parser   = $this->registry->getSubParser( 'Code'.ucfirst($type) );
    
    if( $parser )
      return $parser->parse();
      
    $builder = LibGenfBuild::getDefault();
    $builder->dumpError( "Missing Subparser ".ucfirst($type) );
      
    return '// missing subparser '.ucfirst($type).NL;


  }//end public function compileCode */

  /**
   * @param array $index
   */
  public function appendAffectedSources( $index )
  {

    $parser   = $this->registry->getSubParser( 'Sources' );
    $tmpIndex = $parser->parse();

    foreach( $tmpIndex as $key => $tmpSrc )
    {
      $index[$key] = true;
    }

    return $index;

  }//end public function appendAffectedSources */

  /**
   * @param TTabJoin $index
   */
  public function appendAffectedJoins( $tables )
  {

    /**/
    $parser   = $this->registry->getSubParser('Joins');
    $tmpIndex = $parser->parse();

    foreach( $tmpIndex as $tmpSrc )
    {
      $tables->joins[] = array
      (
        null,                     // join
        $tmpSrc['src'],
        $tmpSrc['srcId'],
        $tmpSrc['target'],
        $tmpSrc['targetId'],
        null,                       // where
        $tmpSrc['alias'],           // alias
        'filter path '.$tmpSrc['target']    // comment
      );

      $tables->index[$tmpSrc['alias']] = array
      (
        null,                     // join
        $tmpSrc['src'],
        $tmpSrc['srcId'],
        $tmpSrc['target'],
        $tmpSrc['targetId'],
        null,                       // where
        $tmpSrc['alias'],           // alias
        'filter path '.$tmpSrc['target']    // comment
      );

    }
    

  }//end public function appendAffectedJoins */


} // end class LibBdlParser

