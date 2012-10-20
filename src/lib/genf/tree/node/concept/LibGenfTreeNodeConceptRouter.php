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
class LibGenfTreeNodeConceptRouter
  extends LibGenfTreeNodeConcept
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getMessage()
  {
   
    if( !isset( $this->node->message ) )
      return null;
      
    return $this->i18nValue( $this->node->message );
    
  }//end public function getMessage */

  /**
   * @return string
   */
  public function getRouteAttribute()
  {
    return trim($this->node->attribute['name']);
  }//end public function getRouteAttribute */
  
  /**
   * @param LibGenfTreeNodeManagement $management
   * @return string
   */
  public function getRouteAttributeTarget( $management, $type = 'name' )
  {
    
    return $management->entity->getAttrTarget( trim($this->node->attribute['name']), $type );

  }//end public function getRouteAttribute */
  
  /**
   * @return string
   */
  public function getRouteField()
  {
    
    if( isset($this->node->attribute['field']) )
    {
      return trim($this->node->attribute['field']);
    }
    else
    {
      return null;
    }
    
  }//end public function getRouteField */

  /**
   * @return string
   */
  public function getDisplayFields()
  {

    if( !isset( $this->node->display->fields->field ) )
    {
      $this->reportError( "Missing Display Fields" );
      return array();
    }
    
    $fields = array();
    
    foreach( $this->node->display->fields->field as $field )
    {
      $fields[] = trim( $field['name'] );
    }
  
    return $fields;
    
  }//end public function getRouteField */
  
  /**
   * @return array<string>
   */
  public function getRoutes( $context = null )
  {
    
    $routeBase = null;
    
    foreach( $this->node->routes as $routesNode )
    {
      
      // eine allgemeine route
      if( !$context )
      {
        if( !isset($routesNode['context']) )
        {
          $routeBase = $routesNode;
          break;
        } 
      }
      else
      {
        if( !isset($routesNode['context']) )
        {
          continue;
        } 
        else 
        {
          if( $context == $routesNode['context'] )
          {
            $routeBase = $routesNode;
            break;
          }
        }
      }
    }
    
    if( !$routeBase )
      return null;
      
    $routes = array();
      
    foreach( $routeBase->route as $route )
    {
      $routes[] = array
      ( 
        'value'  => trim( $route['value'] ), 
        'target' => trim( $route['target'] ),
        'target_name' => SParserString::subToCamelCase( trim( $route['target'] ) ),
      );
    }
    
    return $routes;
    
  }//end public function getRoutes */
  
  /**
   * @return string
   */
  public function getDefaultRoute( $asName = false, $context = null )
  {
    
    $routes = null;
    
    foreach( $this->node->routes as $routesNode )
    {
      
      // eine allgemeine route
      if( !$context )
      {
        if( !isset($routesNode['context']) )
        {
          $routes = $routesNode;
          break;
        } 
      }
      else
      {
        if( !isset($routesNode['context']) )
        {
          continue;
        } 
        else 
        {
          if( $context == $routesNode['context'] )
          {
            $routes = $routesNode;
            break;
          }
        }
      }
    }
    
    if( !$routes )
      return null;

    if( $asName )
    {
      return SParserString::subToCamelCase( trim($this->node->routes->default['target']) ) ;
    }
    else 
    {
      return trim($this->node->routes->default['target']);
    }
    
    
  }//end public function getDefaultRoute */
  
  
  /**
   * Prüfen ob der Router überhaupt Einfluss auf diesen context hat
   * @param string $context
   * @return boolean
   */
  public function affectContext( $context )
  {
    
    foreach( $this->node->routes as $routes )
    {
      
      // eine allgemeine route
      if( !isset( $routes['context'] ) )
        return true;
        
      if( $routes['context'] == $context )
        return true;
      
    }
    
    // context ist nicht von der route betroffen
    return false;
    
  }//end public function affectContext */
  
  /**
   * Prüfen ob der Context separat definiert wurde
   * @param string $context
   * @return boolean
   */
  public function definedContext( $context )
  {
    
    foreach( $this->node->routes as $routes )
    {
      
      // eine allgemeine route
      if( !isset( $routes['context'] ) )
        continue;
        
      if( $routes['context'] == $context )
        return true;
      
    }
    
    // context ist nicht von der route betroffen
    return false;
    
  }//end public function definedContext */
  
  /**
   * @param string $message
   */
  public function reportError( $message )
  {
    
    $this->builder->error( "Invalid Concept Router ".$message." ".$this->builder->dumpEnv() );
    
  }//end public function reportError */

}//end class LibGenfTreeNodeConceptRouter

