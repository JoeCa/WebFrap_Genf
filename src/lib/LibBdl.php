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
class LibBdl
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibBdlCodeParser
   */
  protected $codeCompiler = null;

  /**
   *
   * @var LibBdlFilterParser
   */
  protected $filterParser = null;

  /**
   * Compiler zum bauen des ACL Codes
   * @var LibBdlAclCompiler
   */
  protected $aclCompiler  = null;


////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param LibGenfBuild $builder
   */
  public function __construct( $builder )
  {

    $this->codeCompiler = new LibBdlCodeParser( $builder );
    $this->filterParser = new LibBdlFilterParser( $builder );
    $this->aclCompiler  = new LibBdlAclCompiler( $builder );

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibBdlCodeParser
   */
  public function getCodeCompiler()
  {

    return $this->codeCompiler;

  }//end public function getCodeCompiler */

  /**
   * @return LibBdlFilterParser
   */
  public function getFilterParser()
  {

    return $this->filterParser;

  }//end public function getFilterParser */


  /**
   * @return LibBdlAclCompiler
   */
  public function getAclCompiler()
  {

    return $this->aclCompiler;

  }//end public function LibBdlAclCompiler */

}//end class LibBdl







