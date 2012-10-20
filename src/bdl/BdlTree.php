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
class BdlTree
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var BdlTree
   */
  public static $root = null;
  
////////////////////////////////////////////////////////////////////////////////
// 
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Liste aller Entities
   * @var array<BdlEntity>
   */
  protected $entities     = array();
  
  /**
   * Liste aller Management Nodes
   * @var array<BdlManagement>
   */
  protected $managements  = array();
  
  /**
   * Liste aller Module Nodes
   * @var array<BdlModule>
   */
  protected $modules      = array();
  
  /**
   * Liste aller Widget Nodes
   * @var array<BdlWidget>
   */
  protected $widgets      = array();
  
  /**
   * Liste aller Gateway Nodes
   * @var array<BdlGateway>
   */
  protected $gateways     = array();
  
////////////////////////////////////////////////////////////////////////////////
// getter für root
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return BdlTree
   */
  public static function getRoot()
  {
    
    if( !self::$root )
      self::$root = new BdlTree();
      
    return self::$root;
    
  }//end public static function getRoot */
  
////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $key
   * @param boolean $create
   * 
   * @return BdlNodeEntity
   */
  public function getEntity( $key, $create = true )
  {
    
    if( !isset( $this->entities[$key] ) )
    {
      if( $create )
      {
        $this->entities[$key] = new BdlNodeEntity( $key );
        $this->managements[$key] = new BdlNodeManagement( $key );
        
        $modKey = SParserString::getFirstHump( $key );
        if( !isset( $this->modules[$modKey] ) )
        {
          $this->modules[$modKey] = new BdlNodeModule( $key );
        }
        
      }
      else 
      {
        return null;
      }
    }
    
    return $this->entities[$key];

  }//end public function getEntity */
  
  

}//end class BdlTree
