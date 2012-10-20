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
class BdlNodeRole
  extends BdlBaseNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array
   */
  public $areaPermissions = array();
  
  /**
   * @var array
   */
  public $backPaths = array();

////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param BdlFile $file
   */
  public function __construct( $file  )
  {
    
    $this->file = $file;
    
    $this->dom = $this->file->getNodeByPath('/bdl/roles/role');
    
  }//end public function __construct */
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getName()
  {
    return $this['name'];
  }//end  public function getAuthor
  
  /**
   * @param string $name
   */
  public function setName( $name )
  {
    $this['name'] = $name ;
  }//end  public function setName */
  
  /**
   * @return string
   */
  public function getModule()
  {
    return $this['module'];
  }//end  public function getModule */
  
  /**
   * @param string $name
   */
  public function setModule( $module )
  {
    $this['module'] = $module ;
  }//end  public function setModule */

////////////////////////////////////////////////////////////////////////////////
// Area Permissions
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return [BdlNodeRoleAreaPermission]
   */
  public function getAreaPermissions()
  {
    
    if( $this->areaPermissions )
      return $this->areaPermissions;

    $areas = $this->getNodes( 'permission/areas/area' );
    
    foreach( $areas as $area )
    {
      $this->areaPermissions[] = new BdlNodeRoleAreaPermission( $this->file, $area, $this->dom );
    }
    
    return $this->areaPermissions;
      
  }//end public function getAreaPermissions */
  
  /**
   * @return [BdlNodeRoleAreaPermission]
   */
  public function createPermission()
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();

    $areas = $this->getNode( 'permission/areas' );
    
    if( !$areas  )  
      $areas = $this->touchNode( 'permission/areas' );
    
    $area = $areas->ownerDocument->createElement ( 'area' );
    $areas->appendChild( $area );
    
    $node = new BdlNodeRoleAreaPermission( $this->file, $area, $this->dom );
    $this->areaPermissions[] = $node;
    
    return $node;
      
  }//end public function createPermission */
  
  /**
   * @return BdlNodeRoleAreaPermission
   */
  public function getPermissionByIndex( $index )
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();

    return isset( $this->areaPermissions[$index] )
      ? $this->areaPermissions[$index]
      : null;
      
  }//end public function getPermissionByIndex */
  
  /**
   * @param int $index
   */
  public function deletePermission( $index )
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();
      
    Debug::console( 'drop index '.$index );

    if( isset( $this->areaPermissions[$index] ) )
    {
      $this->areaPermissions[$index]->dom->parentNode->removeChild( $this->areaPermissions[$index]->dom );
      unset( $this->areaPermissions[$index] );
    }
    
    $this->areaPermissions = array_merge( array(), $this->areaPermissions );

  }//end public function deletePermission */
  
  /**
   * @return int
   */
  public function countAreaPermissions()
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();

    return count($this->areaPermissions);
      
  }//end public function countAreaPermissions */
  
////////////////////////////////////////////////////////////////////////////////
// Permission References
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $path
   * @return BdlNodeRoleAreaPermissionRef
   */
  public function getRefByPath( $path )
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();
      
    $pathStack = explode( '.', (string)$path );

    if( 1 == count($pathStack) )
    {
      return $this->areaPermissions[$pathStack[0]];
    }
    else
    {
      $permIdx = array_shift($pathStack);
      return $this->areaPermissions[$permIdx]->getRefByPath( $pathStack );
    }
    
  }//end public function getRefByPath */


  /**
   * @return [BdlNodeRoleAreaPermission]
   */
  public function createPermissionRef( $path )
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();
      
    $pathStack = explode( '.', (string)$path );

    if( 1 == count($pathStack) )
    {
      $refParent = $this->areaPermissions[$pathStack[0]];
    }
    else
    {
      $permIdx = array_shift($pathStack);
      $refParent = $this->areaPermissions[$permIdx]->getRefByPath( $pathStack );
    }
    
    return $refParent->createPermissionRef(  );
      
  }//end public function getAreaPermissions */
  
  /**
   * @param string $path
   * @return int
   */
  public function countAreaRefPermissions( $path )
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();
      
    $pathStack = explode( '.', (string)$path );

    if( 1 == count($pathStack) )
    {
      $refParent = $this->areaPermissions[$pathStack[0]];
    }
    else
    {
      $permIdx   = array_shift( $pathStack );
      $refParent = $this->areaPermissions[$permIdx]->getRefByPath( $pathStack );
    }
    
    return $refParent->countPermissionRef(  );
      
  }//end public function countAreaRefPermissions */
  
  
  /**
   * @param string $path
   * @return int
   */
  public function deletePermissionRef( $path  )
  {
    
    if( !$this->areaPermissions )
      $this->getAreaPermissions();
      
    $pathStack = explode( '.', (string)$path );

    if( 1 == count($pathStack) )
    {
      $this->deletePermission( $pathStack[0] );
    }
    else
    {
      $permIdx = array_shift( $pathStack );
      $this->areaPermissions[$permIdx]->deleteRefByPath( $pathStack );
    }

  }//end public function deletePermissionRef */

////////////////////////////////////////////////////////////////////////////////
// Backpath Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return [BdlNodeProfileAreaBackpath]
   */
  public function getBackpaths()
  {
    
    if( $this->backPaths )
      return $this->backPaths;

    $areas = $this->getNodes( 'permission/backpaths/path' );
    
    foreach( $areas as $area )
    {
      $this->backPaths[] = new BdlNodeProfileAreaBackpath( $this->file, $area, $this->dom );
    }
    
    return $this->backPaths;
      
  }//end public function getBackpaths */
  
  /**
   * @return [BdlNodeProfileAreaBackpath]
   */
  public function createBackpath()
  {
    
    if( !$this->backPaths )
      $this->getBackpaths();

    $paths = $this->getNode( 'permission/backpaths' );
    
    if( !$paths  )  
      $paths = $this->touchNode( 'permission/backpaths' );
    
    $path = $paths->ownerDocument->createElement ( 'path' );
    $paths->appendChild( $path );
    
    $node = new BdlNodeProfileAreaBackpath( $this->file, $path, $this->dom );
    $this->backPaths[] = $node;
    
    return $node;
      
  }//end public function createBackpath */
  
  /**
   * @return BdlNodeProfileAreaBackpath
   */
  public function getBackpathByIndex( $index )
  {
    
    if( !$this->backPaths )
      $this->getBackpaths();

    return isset( $this->backPaths[$index] )
      ? $this->backPaths[$index]
      : null;
      
  }//end public function getBackpathByIndex */
  
  /**
   * @param int $index
   */
  public function deleteBackpath( $index )
  {
    
    if( !$this->backPaths )
      $this->getBackpaths();
      
    Debug::console( 'drop index '.$index );

    if( isset( $this->backPaths[$index] ) )
    {
      $this->backPaths[$index]->dom->parentNode->removeChild( $this->backPaths[$index]->dom );
      unset( $this->backPaths[$index] );
    }
    
    $this->backPaths = array_merge( array(), $this->backPaths );

  }//end public function deleteBackpath */
  
  /**
   * @return int
   */
  public function countBackpaths()
  {
    
    if( !$this->backPaths )
      $this->getBackpaths();

    return count($this->backPaths);
      
  }//end public function countBackpaths */
  
////////////////////////////////////////////////////////////////////////////////
// Backpath Nodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $pathStack
   * @return BdlNodeProfileAreaBackpathNode
   */
  public function getBackpathNodeByPath( $pathStack )
  {
    
    if( !$this->backPaths )
      $this->getBackpaths( );
      
    $pathStack = explode( '.', (string)$pathStack );

    if( 1 == count($pathStack) )
    {
      return $this->backPaths[$pathStack[0]];
    }
    else
    {
      $permIdx = array_shift($pathStack);
      return $this->backPaths[$permIdx]->getBackpathNodeByPath( $pathStack );
    }
    
  }//end public function getBackpathNodeByPath */


  /**
   * @return [BdlNodeProfileAreaBackpathNode]
   */
  public function createBackpathNode( $path )
  {
    
    if( !$this->backPaths )
      $this->getBackpaths( );
      
    $pathStack = explode( '.', (string)$path );

    if( 1 == count($pathStack) )
    {
      $refParent = $this->backPaths[$pathStack[0]];
    }
    else
    {
      $permIdx = array_shift($pathStack);
      $refParent = $this->backPaths[$permIdx]->getBackpathNodeByPath( $pathStack );
    }
    
    return $refParent->createBackpathNode(  );
      
  }//end public function createBackpathNode */
  
  /**
   * @param string $path
   * @return int
   */
  public function countBackpathNodes( $path )
  {
    
    if( !$this->backPaths )
      $this->getBackpaths( );
      
    $pathStack = explode( '.', (string)$path );

    if( 1 == count($pathStack) )
    {
      $refParent = $this->backPaths[$pathStack[0]];
    }
    else
    {
      $permIdx   = array_shift( $pathStack );
      $refParent = $this->backPaths[$permIdx]->getBackpathNodeByPath( $pathStack );
    }
    
    return $refParent->countBackpathNodes(  );
      
  }//end public function countBackpathNodes */
  
  
  /**
   * @param string $path
   * @return int
   */
  public function deleteBackpathNode( $path  )
  {
    
    if( !$this->backPaths )
      $this->getBackpaths( );
      
    $pathStack = explode( '.', (string)$path );

    if( 1 == count($pathStack) )
    {
      $this->deleteBackpath( $pathStack[0] );
    }
    else
    {
      $permIdx = array_shift( $pathStack );
      $this->backPaths[$permIdx]->deleteBackpathNodeByPath( $pathStack );
    }

  }//end public function deleteBackpathNode */
  
}//end class BdlNodeRole
