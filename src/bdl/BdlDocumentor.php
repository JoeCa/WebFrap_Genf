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
class BdlDocumentor
  extends BdlBaseNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibDbPostgresql
   */
  public $db = null;

  /**
   * @var LibDbOrm
   */
  public $orm = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param BdlFile $file
   */
  public function __construct( $db  )
  {
    
    $this->db   = $db;
    $this->orm  = $db->getOrm();
    
  }//end public function __construct */
  

}//end class BdlDocumentor
